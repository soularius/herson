<?php

function custom_rest_route_attributes_cellars_erp_id_item() {
    register_rest_route( 'erp/v1', '/attributes/cellars/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => 'custom_rest_route_attributes_callback',
    ) );
}
add_action( 'rest_api_init', 'custom_rest_route_attributes_cellars_erp_id_item' );

function custom_rest_route_attributes_callback( $data ) {

    if (!is_user_logged_in()) {
        return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
    }

    if (isset($data['id'])) {
        $args = array(
            'taxonomy' => 'pa_bodegas',
            'hide_empty' => false,
            'parent' => 0,
            'meta_query'    => array(
                'relation'        => 'AND',
                array(
                    'key'         => 'id_bodega',
                    'value'          => $data['id'],
                    'compare'     => '=',
                )
            ) 
        );
        $attributes = get_terms( $args );
        if (!empty($attributes)) {
            return $attributes[0];
        }

        return new WP_Error( 'rest_attributes_not_found', 'Attributes not found.', array( 'status' => 401 ) );

    }
}