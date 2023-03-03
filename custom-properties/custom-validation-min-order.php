<?php

    add_action( 'woocommerce_checkout_process', 'minimum_order_amount' );
    //add_action( 'woocommerce_before_cart_contents', 'minimum_order_amount' );
    add_action( 'woocommerce_check_cart_items', 'minimum_order_amount' );

    function minimum_order_amount() {
        // Set this to the minimum order amount
        $zona = "";
        $minimum = 0;
        $user_id = get_current_user_id();

        if(get_field("zona", "user_$user_id")) {
            $zona = get_field("zona", "user_$user_id");
        }
        if($zona == "urbano") {
            $minimum = get_field('costo_pedido_minimo_urbano', 'option');
        }

        if($zona == "rural") {
            $minimum = get_field('costo_pedido_minimo_rural', 'option');
        }

        $minimum = intval($minimum);

        if ( WC()->cart->total < $minimum ) {
            wc_add_notice( sprintf( 'Para finalizar la compra requiere un valor mínimo de %s, el total actual de su pedido es %s.' ,
                wc_price( $minimum ),
                wc_price( WC()->cart->total )
            ), 'error' );
        }
    }