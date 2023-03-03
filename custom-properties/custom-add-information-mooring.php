<?php

add_action('woocommerce_single_product_summary', 'add_group_mooring_products', 6);

function add_group_mooring_products()
{
    $product = wc_get_product();
    $product_id = $product->get_id();
    $mooring = get_field("producto_amarre", $product_id);

    if ($mooring === true) {
        $all_data = get_field("producto_en_sede", $product_id);
        $sedes = array_column($all_data, "sede");
        $default_variation = get_product_default_variation();
        $key = array_search($default_variation, $sedes);
        $acf_product_morning_list = $all_data[$key]["listado_de_productos"];

        if ($key !== false) {
            $total_items = count($acf_product_morning_list);
            $counter = 1;
            echo "<div class='box-mooring'>";
            echo "<h2 class='tittle-mooring color-1 fw-600 mt-4'>Productos Amarrados</h2>";
            echo "<div class='box-mooring-item d-flex flex-column justify-content-center align-items-start'>";
            foreach ($acf_product_morning_list as $key => $acf_product_mooring) {
                $product_mooring = $acf_product_mooring["producto"];
                $obj_product_mooring = wc_get_product($product_mooring->ID);
                get_template_part(
                    '/template-parts/content',
                    'product-mooring',
                    [
                        'acf_product_morning' => $acf_product_mooring,
                        'product_mooring' => $obj_product_mooring,
                        'counter' => $counter,
                        'total_items' => $total_items
                    ]
                );
                $counter++;
            }
            echo "</div>";
            echo "</div>";
        }
    }
}

add_action('woocommerce_cart_item_name', 'display_acf_field_in_cart', 10, 3);

function display_acf_field_in_cart($product_name, $cart_item, $cart_item_key)
{
    // Obtén el ID del producto
    $product_id = $cart_item['product_id'];
    $mooring = get_field("producto_amarre", $product_id);

    if ($mooring === true) {
        $all_data = get_field("producto_en_sede", $product_id);
        $sedes = array_column($all_data, "sede");
        $default_variation = get_product_default_variation();
        $key = array_search($default_variation, $sedes);
        $acf_product_morning_list = $all_data[$key]["listado_de_productos"];

        if ($key !== false) {
            $total_items = count($acf_product_morning_list);
            $counter = 1;
            $products_morning = "";
            $products_morning .=  "<div class='box-mooring'>";
            $products_morning .=  "<h2 class='tittle-mooring color-1 fw-600'>Productos Amarrados: </h2>";
            $products_morning .=  "<div class='box-mooring-item d-flex flex-column justify-content-center align-items-start'>";
            foreach ($acf_product_morning_list as $key => $acf_product_mooring) {
                $product_mooring = $acf_product_mooring["producto"];
                $obj_product_mooring = wc_get_product($product_mooring->ID);
                $products_morning .= "<div class='item-mooring-cart d-flex'>";
                $products_morning .= "<span class='name-items-mooring'>" . $obj_product_mooring->get_name() . ", cant. " . $acf_product_mooring["cantidad"] . "</span>";
                /* if ($counter < $total_items) {
                                $products_morning .= "<div class='box-simbol-polus'>";
                                $products_morning .= "<span class='simbol-plus color-1'> + </span>";
                                $products_morning .= "</div>";
                            } */
                $products_morning .= "</div>";
                $counter++;
            }
            $products_morning .=  "</div>";
            $products_morning .=  "</div>";
        }
    }


    // Agrega el valor del campo ACF debajo del nombre del producto
    return $product_name . '<div class="dato-acf">' . $products_morning . '</div>';
}

function get_product_default_variation()
{
    $term = search_bodega();
    $term = $term[0];

    return $term->term_id;
}
