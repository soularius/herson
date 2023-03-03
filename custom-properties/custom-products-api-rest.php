<?php

function custom_rest_route_product_erp_id_item()
{
    register_rest_route('erp/v1', '/product/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'custom_rest_route_products_callback',
    ));

    register_rest_route('erp/v1', '/product/(?P<sku>.+)', array(
        'methods' => 'GET',
        'callback' => 'product_by_sku_api',
    ));

    register_rest_route('erp/v1', '/mooring-products', array(
        'methods' => 'POST',
        'callback' => 'update_acf_fields_via_rest_mooring_products',
    ));

    register_rest_route('erp/v1', '/attributes-products', array(
        'methods' => 'POST',
        'callback' => 'register_acf_fields_via_rest_products',
    ));
}
add_action('rest_api_init', 'custom_rest_route_product_erp_id_item');

function custom_rest_route_products_callback($data)
{
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }

    if (isset($data['id'])) {
        $args = array(
            'post_type' => 'product',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
            'meta_query'    => array(
                'relation'        => 'AND',
                array(
                    'key'         => 'id_item',
                    'value'          => $data['id'],
                    'compare'     => '=',
                )
            )
        );

        $product = new WP_Query($args);
        $arg_product = null;
        $id_product = 0;

        if ($product->have_posts()) {
            while ($product->have_posts()) {
                $product->the_post();
                $id_product = get_the_ID();
                $arg_product = wc_get_product($id_product);
                $attributes = $arg_product->get_attributes();
                $data = $arg_product->get_data();
                $data['attributes'] = array();
                foreach ($attributes as $key => $attribute) {
                    $options = array();
                    foreach ($attribute->get_options() as $option) {
                        $term = get_term($option);
                        if ($term) {
                            $options[] = $term->name;
                        }
                    }
                    $data['attributes'][] = array(
                        'id' => $attribute->get_id(),
                        'name' => $attribute->get_name(),
                        'position' => $attribute->get_position(),
                        'visible' => $attribute->get_visible(),
                        'variation' => $attribute->get_variation(),
                        'options' => $options
                    );
                }
                return $data;
            }
            wp_reset_postdata();
        }
        return new WP_Error('rest_product_not_found', 'Product not found.', array('status' => 401));
    }
}

function product_by_sku_api($data)
{
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }

    if (isset($data['sku'])) {
        $args = array(
            'post_type' => 'product',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
            'meta_query'    => array(
                'relation'        => 'AND',
                array(
                    'key'         => '_sku',
                    'value'          => $data['sku'],
                    'compare'     => '=',
                )
            )
        );

        $product = new WP_Query($args);
        $arg_product = null;
        $id_product = 0;

        if ($product->have_posts()) {
            while ($product->have_posts()) {
                $product->the_post();
                $id_product = get_the_ID();
                $arg_product = wc_get_product($id_product);
                $attributes = $arg_product->get_attributes();
                $data = $arg_product->get_data();
                $data['attributes'] = array();
                foreach ($attributes as $key => $attribute) {
                    $options = array();
                    foreach ($attribute->get_options() as $option) {
                        $term = get_term($option);
                        if ($term) {
                            $options[] = $term->name;
                        }
                    }
                    $data['attributes'][] = array(
                        'id' => $attribute->get_id(),
                        'name' => $attribute->get_name(),
                        'position' => $attribute->get_position(),
                        'visible' => $attribute->get_visible(),
                        'variation' => $attribute->get_variation(),
                        'options' => $options
                    );
                }
                return $data;
            }
            wp_reset_postdata();
        }
        return new WP_Error('rest_product_not_found', 'Product not found.', array('status' => 401));
    }
}

function register_acf_fields_via_rest_products($request)
{
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    $product_id = null;
    $cum = null;
    $registro_sanitario = null;
    $codigo_de_barras = null;
    $ans = false;

    if (isset($request['cum'])) {
        $cum = $request['cum'];
    }

    if (isset($request['registro_sanitario'])) {
        $registro_sanitario = $request['registro_sanitario'];
    }

    if (isset($request['codigo_de_barras'])) {
        $codigo_de_barras = $request['codigo_de_barras'];
    }

    if (isset($request['product_id'])) {
        $product_id = $request['product_id'];
    }

    update_field('cum', $cum, $product_id);
    update_field('registro_sanitario', $registro_sanitario, $product_id);
    update_field('codigo_de_barras', $codigo_de_barras, $product_id);

    $ans = true;

    if ($ans) {
        return rest_ensure_response('Product updated successfully');
    } else {
        return new WP_Error('rest_not_update_attr_products', 'Product not update.', array('status' => 500));
    }
}

function update_acf_fields_via_rest_mooring_products($request)
{
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }
    $product_id = false;
    $keyCode = false;
    $sede = false;

    if (isset($request['id'])) {
        $product_id = $request['id'];
    }

    if (isset($request['item_id'])) {
        $product_id = search_id_product_by_item_id($request['item_id']);
    }

    if (isset($request['sku'])) {
        $product_id = search_id_product_by_sku($request['sku']);
    }

    if (!$product_id) {
        return;
    }

    if (isset($request["sede"])) {
        $celler = search_cellars_by_celler($request["sede"]);
        $sede = $celler->term_id;
    }

    if (!$sede) {
        return;
    }

    $listado_de_productos = $request['listado_de_productos'];

    foreach ($listado_de_productos as $key => $prod) {
        if ($id_p = search_id_product_by_sku($prod["codigo_item"])) {
            $listado_de_productos[$key]["producto"] = $id_p;
        } else {
            unset($listado_de_productos[$key]);
        }
    }

    update_field('producto_amarre', true, $product_id);

    $all_data = get_field("producto_en_sede", $product_id);
    $sedes = array_column($all_data, "sede");
    $key = array_search($sede, $sedes);

    if (count($listado_de_productos) == 1) {
        $list = $all_data[$key]["listado_de_productos"];
        $list_codes = array_column($list, "codigo_item");
        $new_item = $listado_de_productos[0];
        $keyCode = array_search($new_item["codigo_item"], $list_codes);
    }

    $rows = array(
        'acf_fc_layout' => "amarre_sede",
        "sede" => $sede,
        "listado_de_productos" => $listado_de_productos,
    );

    $ans = false;

    if ($key !== false && $keyCode !== false) {
        $all_data[$key]["listado_de_productos"][$keyCode] = $new_item;
        $rows["listado_de_productos"] = $all_data[$key]["listado_de_productos"];
        //return delete_row('producto_en_sede', $key+1, $product_id);
        $ans = update_row('producto_en_sede', $key + 1, $rows, $product_id);
    } else {
        if ($key !== false && $keyCode === false) {
            array_push($all_data[$key]["listado_de_productos"], $new_item);
            $rows["listado_de_productos"] = $all_data[$key]["listado_de_productos"];
            $ans = update_row('producto_en_sede', $key + 1, $rows, $product_id);
        } else {
            $ans = add_row('producto_en_sede', $rows, $product_id);
        }
    }

    if ($ans) {
        return rest_ensure_response('Moorings updated successfully');
    } else {
        return new WP_Error('rest_not_update_mooring', 'Moorings not update.', array('status' => 500));
    }
}

function search_id_product_by_item_id($item_id)
{
    $args = array(
        'post_type' => 'product',
        'post_status'   => 'publish',
        'posts_per_page' => -1,
        'meta_query'    => array(
            'relation'        => 'AND',
            array(
                'key'         => 'id_item',
                'value'          => $item_id,
                'compare'     => '=',
            )
        )
    );

    $products = get_posts($args);
    if (count($products)) {
        return $products[0]->ID;
    } else {
        return false;
    }
}

function search_id_product_by_sku($sku)
{
    $args = array(
        'post_type' => 'product',
        'post_status'   => 'publish',
        'posts_per_page' => -1,
        'meta_query'    => array(
            'relation'        => 'AND',
            array(
                'key'         => '_sku',
                'value'          => $sku,
                'compare'     => '=',
            )
        )
    );

    $products = get_posts($args);
    if (count($products)) {
        return $products[0]->ID;
    } else {
        return false;
    }
}

function search_cellars_by_celler($id_cell)
{
    $args = array(
        'taxonomy' => 'pa_bodegas',
        'hide_empty' => false,
        'parent' => 0,
        'meta_query'    => array(
            'relation'        => 'AND',
            array(
                'key'         => 'id_bodega',
                'value'          => $id_cell,
                'compare'     => '=',
            )
        )
    );
    $attributes = get_terms($args);
    if (!empty($attributes)) {
        return $attributes[0];
    }
    return false;
}
