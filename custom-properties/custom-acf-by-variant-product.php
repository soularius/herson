<?php

// -----------------------------------------
// 1. Add custom field input @ Product Data > Variations > Single Variation
 
add_action( 'woocommerce_variation_options', 'add_custom_field_to_variations', 10, 3 );
 
function add_custom_field_to_variations( $loop, $variation_data, $variation ) {
    echo '<div class="options_group form-row form-row-first">';
    woocommerce_wp_text_input( array(
            'id' => 'custom_field_variant_id_item[' . $loop . ']',
            'class' => 'short',
            'label' => __( 'Id Item', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'custom_field_variant_id_item', true ),
            'desc_tip'    => true,
            'description' => __( "Este es el id del item relacionado con el ERP.", "woocommerce" ),
            'placeholder' => __('Ingrese el id del item relacionado al ERP', 'woocomerce')
    ) );
    echo '</div>';

    echo '<div class="options_group form-row form-row-last">';
    woocommerce_wp_text_input( array(
            'id' => 'custom_field_variant_id_bodega[' . $loop . ']',
            'class' => 'short',
            'label' => __( 'Id Bodega', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'custom_field_variant_id_bodega', true ),
            'desc_tip'    => true,
            'description' => __( "Esta es el id que relaciona la variacion de bodega con el ERP.", "woocommerce" ),
            'placeholder' => __('Ingrese el id de la bodega relacionado al ERP', 'woocomerce')
    ) );
   echo '</div>';

    echo '<div class="options_group form-row form-row-first">';
    woocommerce_wp_text_input( array(
            'id' => 'custom_field_variant_qty_min[' . $loop . ']',
            'class' => 'short',
            'label' => __( 'Cantidad Minima', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'custom_field_variant_qty_min', true ),
            'desc_tip'    => true,
            'description' => __( "Esta es la cantidad minima que un cliente puede comprar por producto.", "woocommerce" ),
            'placeholder' => __('Ingrese cantidad minima de compra por producto', 'woocomerce')
    ) );
    echo '</div>';

    echo '<div class="options_group form-row form-row-last">';
    woocommerce_wp_text_input( array(
            'id' => 'custom_field_variant_qty_max[' . $loop . ']',
            'class' => 'short',
            'label' => __( 'Cantidad Maxima', 'woocommerce' ),
            'value' => get_post_meta( $variation->ID, 'custom_field_variant_qty_max', true ),
            'desc_tip'    => true,
            'description' => __( "Esta es la cantidad maxima que un cliente puede comprar por producto.", "woocommerce" ),
            'placeholder' => __('Ingrese cantidad maxima de compra por producto', 'woocomerce')
    ) );
   echo '</div>';
}
 
// -----------------------------------------
// 2. Save custom field on product variation save
 
add_action( 'woocommerce_save_product_variation', 'save_custom_field_variations', 10, 2 );
 
function save_custom_field_variations( $variation_id, $i ) {
   $custom_field = $_POST['custom_field_variant_id_bodega'][$i];
   if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'custom_field_variant_id_bodega', esc_attr( $custom_field ) );

   $custom_field = $_POST['custom_field_variant_id_item'][$i];
   if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'custom_field_variant_id_item', esc_attr( $custom_field ) );

   $custom_field = $_POST['custom_field_variant_qty_max'][$i];
   if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'custom_field_variant_qty_max', esc_attr( $custom_field ) );

   $custom_field = $_POST['custom_field_variant_qty_min'][$i];
   if ( isset( $custom_field ) ) update_post_meta( $variation_id, 'custom_field_variant_qty_min', esc_attr( $custom_field ) );
}
 
// -----------------------------------------
// 3. Store custom field value into variation data
 
add_filter( 'woocommerce_available_variation', 'add_custom_field_variation_data' );
 
function add_custom_field_variation_data( $variations ) {
   $variations['custom_field_variant_id_bodega'] = get_post_meta( $variations[ 'variation_id' ], 'custom_field_variant_id_bodega', true );
   $variations['custom_field_variant_id_item'] = get_post_meta( $variations[ 'variation_id' ], 'custom_field_variant_id_item', true );
   $variations['custom_field_variant_qty_max'] = get_post_meta( $variations[ 'variation_id' ], 'custom_field_variant_qty_max', true );
   $variations['custom_field_variant_qty_min'] = get_post_meta( $variations[ 'variation_id' ], 'custom_field_variant_qty_min', true );
   return $variations;
}