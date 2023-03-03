<?php

function custom_woocommerce_register_post_statuses() {
    register_post_status( 'wc-procesarpendiente', array(
        'label' => 'Pendiente por procesar',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Pendiente por procesar <span class="count">(%s)</span>', 'Pendiente por procesar <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-alistamiento', array(
        'label' => 'Alistamiento',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Alistamiento <span class="count">(%s)</span>', 'Alistamiento <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-facturado', array(
        'label' => 'Facturado',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Facturado <span class="count">(%s)</span>', 'Facturado <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-despachado', array(
        'label' => 'Despachado',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Despachado <span class="count">(%s)</span>', 'Despachado <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'custom_woocommerce_register_post_statuses' );


// Add to list of WC Order statuses
function add_custom_order_statuses( $order_statuses ) {
    $order_statuses['wc-procesarpendiente'] = _x( 'Pendiente por procesar', 'Order status', 'woocommerce' );
    $order_statuses['wc-alistamiento'] = _x( 'Alistamiento', 'Order status', 'woocommerce' );
    $order_statuses['wc-facturado'] = _x( 'Facturado', 'Order status', 'woocommerce' );
    $order_statuses['wc-despachado'] = _x( 'Despachado', 'Order status', 'woocommerce' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'add_custom_order_statuses' );

add_action( 'woocommerce_order_status_changed', 'code_status_changes', 10, 3 );
function code_status_changes( $order_id, $old_status, $new_status ) {
  // Agregamos o actualizamos la meta data del pedido
    $code_value = null;
    switch($new_status) 
    {
        case "procesarpendiente":
            $code_value = 1;
        break;
        case "alistamiento":
            $code_value = 2;
        break;
        case "facturado":
            $code_value = 3;
        break;
        case "despachado":
            $code_value = 4;
        break;
    }
    if (!is_null($new_status)) {
        update_post_meta($order_id, 'code_field', $code_value);
    }
}


function custom_woocommerce_admin_css() {
    ?>
        <style>
            .status-procesarpendiente {
                background-color: #ecdfe8;
                color: #581845 ;
            }
            .status-alistamiento {
                background-color: #fdccc7;
                color: #856404;
            }
            .status-facturado {
                background-color: #c3e6cb;
                color: #155724;
            }
            .status-despachado {
                background-color: #c8dadf;
                color: #0c5460;
            }
        </style>
    <?php
}
add_action( 'admin_head', 'custom_woocommerce_admin_css' );

