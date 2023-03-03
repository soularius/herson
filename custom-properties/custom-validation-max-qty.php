<?php

    add_filter( 'woocommerce_add_to_cart_validation', 'filter_woocommerce_add_to_cart_validation', 10, 5 );
    function filter_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null, $variations = null, $cart = false ) {
        $product = wc_get_product( $product_id );        
        $data_now = strtotime("now");
        if ($product->is_type( 'variable' )) {
            $flag = false;
            $variation_info = $product->get_child($variation_id);
            $attribute = $variation_info->get_attributes();

            $taxonomy = 'pa_bodegas';
            $item_quantity_in_cart = 0;
            /* $meta = get_post_meta($variation_id, 'attribute_'.$taxonomy, true);
            $term = get_term_by('slug', $meta, $taxonomy); */
            //$det = $variation_info->get_attribute("pa_bodegas");
            
            $max_qty_shop = $variation_info->get_meta('custom_field_variant_qty_max');
            $max_qty_shop = intval($max_qty_shop);
            $min_qty_shop = $variation_info->get_meta('custom_field_variant_qty_min');
            $min_qty_shop = intval($min_qty_shop);
            $sales_price_from = get_post_meta( $variation_id, '_sale_price_dates_from', true );
            $sales_price_to   = get_post_meta( $variation_id, '_sale_price_dates_to', true );

            $variation = array("attribute_$taxonomy" => $attribute[$taxonomy]);
            $product_cart_id = WC()->cart->generate_cart_id( $product_id, $variation_id, $variation, array());
            $in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

            if($in_cart) {
                $list_products_in_cart = WC()->cart->get_cart();
                $item_in_cart = $list_products_in_cart[$product_cart_id];
                $item_quantity_in_cart = intval($item_in_cart["quantity"]);
            }
            /* var_dump($item_quantity_in_cart);
            var_dump($quantity); */
            //qty products order list and qty in cart
            
            $qty_total_cart = !$cart ? $item_quantity_in_cart + $quantity : $quantity;
            
            if ($min_qty_shop > $qty_total_cart) {
                wc_add_notice( __( "Debe añadir mínimo $min_qty_shop unidades por pedido" , 'woocommerce' ), 'error' );
                return false;
            }

            if ($sales_price_to<$data_now) { return true; }

            if (
                !empty($max_qty_shop) &&
                !empty($sales_price_from) &&
                !empty($sales_price_to) &&
                $sales_price_from<=$data_now &&
                $sales_price_to>=$data_now
            ) {


                $list_order = get_orders_ids_by_variation_id($variation_id);
                $list_order = filter_order_by_data_max($list_order, $variation_id);
                $qty_list_products = get_qty_all_products_variation($list_order);

                //qty items in order last (rang date)
                $acum_total_last_order = array_sum($qty_list_products);

                //qty by validate max shop
                $sum_all_items_by_sale = $acum_total_last_order + $qty_total_cart;

                // Get the product name
                $product_name = $product->get_name();

                if($max_qty_shop >= $sum_all_items_by_sale) {
                    return true;
                } else {
                    wc_add_notice( __( "Límite máximo de compra alcanzado en el producto $product_name", 'woocommerce' ), 'error' );
                    return false;
                }
            }
        }
        return true;
    }


    add_filter( 'woocommerce_update_cart_validation', 'filter_woocommerce_update_cart_validation', 10, 4 );
    function filter_woocommerce_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
        // Obtener el producto a partir del cart_item_key
        $cart_item = WC()->cart->get_cart_item( $cart_item_key );
        $product_id = $cart_item['product_id'];
        $variation = $cart_item['variation_id'];
        $variations = $cart_item['variation'];
        
        // Llamar a la función de validación del "Añadir al carrito" para validar el producto
        $passed = filter_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation, $variations, true );
        return $passed;
    }


        /**
     * Get All orders IDs for a given product ID.
     *
     * @param  integer  $product_id (required)
     * @param  array    $order_status (optional) Default is 'wc-completed'
     *
     * @return array
     */
    function get_orders_ids_by_variation_id($variation_id, $order_status = array( 'wc-procesarpendiente', 'wc-alistamiento', 'wc-facturado', 'wc-despachado', 'wc-processing', 'wc-completed', 'wc-on-hold')) {
        global $wpdb;
        $user_id = get_current_user_id();
        $sql = "
            SELECT order_items.order_id, order_items.order_item_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            LEFT JOIN {$wpdb->postmeta} AS postmeta ON order_items.order_id = postmeta.post_id
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
            AND order_item_meta.meta_key = '_variation_id'
            AND order_item_meta.meta_value = '$variation_id'
            AND postmeta.meta_key = '_customer_user'
            AND postmeta.meta_value = '$user_id'
        ";
        $results = $wpdb->get_results($sql);
        return $results;
    }

    function filter_order_by_data_max($list_order, $variation_id) {
        global $wpdb;
        $data_now = strtotime("now");
        $list_order_item_id = array_column($list_order, "order_item_id");
        $sql = "
            SELECT order_items.order_id, order_items.order_item_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            LEFT JOIN {$wpdb->postmeta} AS postmeta_from ON (order_items.order_id = postmeta_from.post_id) AND (postmeta_from.meta_key = '_sale_price_dates_from') AND (postmeta_from.meta_value >= '$data_now')
            LEFT JOIN {$wpdb->postmeta} AS postmeta_to ON (order_items.order_id = postmeta_to.post_id) AND (postmeta_to.meta_key = '_sale_price_dates_to') AND (postmeta_to.meta_value <= '$data_now')
            WHERE posts.post_type = 'shop_order'
            AND order_items.order_item_id IN ( '" . implode("','", $list_order_item_id) . "' )
            AND order_item_meta.meta_key = '_variation_id'
            AND order_item_meta.meta_value = '$variation_id'
        ";
        $results = $wpdb->get_results($sql);
        return $results;
    }

    function get_qty_all_products_variation( $list_order ) {
        global $wpdb;
        $list_order_item_id = array_column($list_order, "order_item_id");
        $sql = "
            SELECT order_item_meta.meta_value
            FROM {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta
            WHERE order_item_meta.order_item_id IN ( '" . implode("','", $list_order_item_id) . "' )
            AND order_item_meta.meta_key = '_qty'
        ";
        $results = $wpdb->get_results($sql);
        $list_order_qty = array_column($results, "meta_value");
        return $list_order_qty;
    }