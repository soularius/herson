<?php

function insert_products_erp($id_item, $id_product, $code)
{
    global $wpdb;

    $data = array(
        'id_item' => $id_item,
        'id_product' => $id_product,
        'code' => $code,
    );

    $wpdb->insert($wpdb->prefix . 'products_erp', $data);
}

function search_id_products_erp($id_item)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'products_erp';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id_item = %s",
        $id_item
    ));
}

function search_code_products_erp($code)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'products_erp';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE code = %s",
        $code
    ));
}
