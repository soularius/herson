<?php

/** Default bodega para los usuarios en detalle de productos */

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'set_default_variation_option', 10, 1 );

function set_default_variation_option($args) {
    if ( is_user_logged_in() ) {
            $term = search_bodega();
            if(!empty($term)) {
                $term = $term[0];
                $term_slug = $term->slug;
                $args['selected'] = $term_slug;
                return $args;
            }
    }
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'custom_show_variation_attribute_options_html', 10, 2 );

function custom_show_variation_attribute_options_html( $html, $args ) {
    // Obtener las opciones de atributo del producto variable
    $options   = $args['options'];
    $product   = $args['product'];
    $attribute = $args['attribute'];
    $selected = $args['selected'];

    if ( is_user_logged_in() && $attribute == "pa_bodegas") {
        $term = search_bodega();
        if(!empty($term)) {
            $term = $term[0];

            $options = array_filter( $options, function( $option ) use ($term) {
                return $option == $term->slug;
            });
            $html = woocommerce_dropdown_variation_attribute_options_html( $options, $product, $attribute, $selected, $term );
        }
    }
    return $html;
}

function woocommerce_dropdown_variation_attribute_options_html( $options, $product, $attribute, $selected, $term ) {
  // Almacenar los parámetros en la variable $args
    $args = array(
        'options'   => $options,
        'product'   => $product,
        'attribute' => $attribute,
        'selected'  => $selected
    );
  
    $html = '<select id="'.$term->taxonomy.'" class="" name="attribute_'.$term->taxonomy.'" data-attribute_name="attribute_'.$term->taxonomy.'" data-show_option_none="yes">';
  
    foreach ( $args['options'] as $option ) {
        $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
        $taxonomy_option = get_term_by('slug', $option, $term->taxonomy);
        $option_name = $taxonomy_option->name;
        $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html($option_name) . '</option>';
    }

    $html .= '</select>';

  return $html;
}

function search_bodega() {
    // Obtener el ID del usuario logueado
    $user_id = get_current_user_id();
    $id_bodega = "";

    if(get_field("zona", "user_$user_id")) {
        $id_bodega = get_field("id_bodega", "user_$user_id");
    }
    $arg = array(
        'taxonomy' => 'pa_bodegas',
        'meta_query'    => array(
            'relation'        => 'AND',
            array(
                'key'         => 'id_bodega',
                'value'          => $id_bodega,
                'compare'     => '=',
            )
        )
    );
    return get_terms($arg);
}


/** Default bodega para los usuarios en listado de productos */

add_action( 'woocommerce_before_shop_loop_item', 'set_default_product_variation' );
function set_default_product_variation() {
  // Comprueba si el usuario ha iniciado sesión
  if ( is_user_logged_in() ) {
    // Obtén el ID de la variante predeterminada
        // Obtén la variante predeterminada
        $term = search_bodega();
        $term = $term[0];
        // Establece la variante predeterminada
        global $product;
        $variation_list = $product->get_available_variations();
        $product->set_default_attributes( array( 'variation' => $term->slug ) );
        $key = array_search($term->slug, array_column(array_column($variation_list, "attributes"), 'attribute_pa_bodegas'));
        if ($key) {
          $variation_price = $product->get_variation_price('min', true, array('variation' => $term->slug));
        }
  }
}

add_filter( 'woocommerce_get_price_html', 'change_variation_prices_in_list', 10, 2 );
function change_variation_prices_in_list( $price, $product ) {
  // Check if the product has variations
  if ( $product->is_type( 'variable' ) ) {
    // Get the product ID and the variation prices    
      $term = search_bodega();
      $term = $term[0];


      $variation_list = $product->get_available_variations();

      $key = array_search($term->slug, array_column(array_column($variation_list, "attributes"), 'attribute_pa_bodegas'));
    
      if($key === false) {
        return $price;
      }
      $variation_id = $variation_list[$key]["variation_id"];
      $variation = $product->get_child($variation_id);
      $tax_rate = $variation->get_tax_class();

      $price = $variation_list[$key]["display_price"];
      return wc_price( $price ) . ( (!empty($tax_rate)) ? " IVA Incluido" : " Excento de IVA");
  }
    // Return the original price HTML for non-variable products
  return $price;
}

add_action( 'woocommerce_before_shop_loop_item', 'remove_default_add_to_cart_button' );
function remove_default_add_to_cart_button() {
  global $product;
  if ( $product->is_type( 'variable' ) ) {
    
    $term = search_bodega();
    $term = $term[0];
    $variation_list = $product->get_available_variations();

    $key = array_search($term->slug, array_column(array_column($variation_list, "attributes"), 'attribute_pa_bodegas'));

    if($key === false) {
      return;
    }

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
  }
}


add_action( 'woocommerce_after_shop_loop_item', 'custom_add_to_cart_button' );
function custom_add_to_cart_button() {
  global $product;
  if ( $product->is_type( 'variable' ) ) {
    
    $term = search_bodega();
    $term = $term[0];
    $default_attributes = array( 'attribute_pa_bodegas' => $term->slug );
    $variation_list = $product->get_available_variations();
    $product_id = $product->get_id();

    $key = array_search($term->slug, array_column(array_column($variation_list, "attributes"), 'attribute_pa_bodegas'));

    if($key === false) {
      return;
    }

      remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
      
      $variation_id = $variation_list[$key]["variation_id"];
      $variation_info = $product->get_child($variation_id);
      $max_qty_shop = $variation_info->get_meta('custom_field_variant_qty_max');
      $max_qty_shop = intval($max_qty_shop);
      $min_qty_shop = $variation_info->get_meta('custom_field_variant_qty_min');
      $min_qty_shop = intval($min_qty_shop);

      $add_to_cart_url = add_query_arg( array(
          'add-to-cart' => $product_id,
          'variation_id' => $variation_id,
          'attribute_pa_bodegas' => $term->slug,
          'ajax_add_to_cart' => true,          
          '_wpnonce' => wp_create_nonce( 'add_to_cart' ),
          '_wp_http_referer' => wp_unslash( $_SERVER['REQUEST_URI'] ),
      ), get_permalink( $product_id ));

      echo '<form class="add-to-item-cart" action="' . $add_to_cart_url . '" method="post" data-product_id="'.$product_id.'" data-variation_id="'.$variation_id.'">';
        woocommerce_quantity_input( 
                array(
                    'min_value' => empty($min_qty_shop) ? 1 : $min_qty_shop,
                    'max_value' => $product->backorders_allowed() ? '' : $max_qty_shop
                )
              );
        echo '<button type="submit" class="button add_to_cart_button">Añadir al carrito</button>';
      echo '</form>';
      echo '<div class="msg-action-product"></div>';
  }
}

// Filtrar productos del listado
add_action('woocommerce_product_query', 'filter_products_by_variation');
function filter_products_by_variation($query) {
    // Si no estamos en la página de listado de productos, no hacer nada
    if (!is_shop() && !is_product_taxonomy()) {
        return;
    }

    $term = search_bodega();
    $term = $term[0];
    // Añadir una cláusula WHERE a la consulta para filtrar los productos por variación
    $query->set('tax_query', array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'pa_bodegas',
            'field' => 'slug',
            'terms' => $term->slug
        )
    ));

    $query->set('meta_query', array(
        'relation' => 'AND',
        array(
            'key' => 'producto_bonificado',
            'value' => 'true',
            'compare' => "!="
        )
    ));
}

function redirect_hidden_product(){
  if( is_product() ){
      global $post;
      $product_id = $post->ID;
      $ocultar_producto = get_field("producto_bonificado", $product_id);
      if($ocultar_producto){
          wp_redirect( home_url() );
          exit;
      }
  }
}
add_action( 'template_redirect', 'redirect_hidden_product' );

/** Update cart content icon */
function update_cart_info_callback() {
  // Obtener el número de elementos y el valor total del carrito
  $cart_count = WC()->cart->get_cart_contents_count();
  $cart_total = WC()->cart->get_cart_total();
  // Devolver el número de elementos y el valor total del carrito como respuesta
  echo $cart_count . '|' . $cart_total;
  wp_die();
}
add_action( 'wp_ajax_update_cart_info', 'update_cart_info_callback' );
add_action( 'wp_ajax_nopriv_update_cart_info', 'update_cart_info_callback' );
