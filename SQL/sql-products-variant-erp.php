<?php

function insert_products_variants_erp($id_item, $id_product) {
    global $wpdb;

    $data = array(
        'id_celler' => $id_item,
        'id_variation' => $id_product,
    );

    $wpdb->insert($wpdb->prefix . 'cellers_erp', $data);
}

function search_id_product_variants_erp($id_celler) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cellers_erp';

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id_celler = %s",
        $id_celler
    ) );
}