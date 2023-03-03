<?php

function insert_cellers_erp($id_celler, $id_variation, $name) {
    global $wpdb;

    $data = array(
        'id_celler' => $id_celler,
        'id_variation' => $id_variation,
        'name' => $name
    );

    $wpdb->insert($wpdb->prefix . 'cellers_erp', $data);
}

function search_id_cellers_erp($id_celler) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cellers_erp';

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id_celler = %s",
        $id_celler
    ) );
}