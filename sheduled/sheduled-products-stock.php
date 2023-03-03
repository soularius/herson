<?php

//get_template_part('sheduled/utils', 'helps');
get_template_part('ERP/connect', 'products-stock');
get_template_part('SITE/connect', 'products-stock');
get_template_part('SITE/connect', 'cellars-variations');
get_template_part('SQL/sql', 'cellers-erp');

function file_product_stock()
{
        $file = __DIR__ . "/../ERP/tmp/list-product-stock.json";
        echo "IN";
        if (
                get_field('corriendo_productos', 'option') &&
                get_field('corriendo_stock', 'option') &&
                !get_field('corriendo_mooring', 'option')
        ) {
                update_field('corriendo_productos', false, 'option');
                update_field('corriendo_stock', true, 'option');
                update_field('posicion_actual_stock', 0, 'option');
                products_stock_erp();
                clearstatcache();
                sleep(3);
                format_json($file);
        }

        if (get_field('corriendo_stock', 'option')) {
                if (file_exists($file)) {
                        if (is_readable($file)) {
                                clearstatcache();
                                sleep(3);
                                process_file_products_stock($file);
                        } else {
                                echo "File is not readable";
                                write_log_error("File {$file} is not readable");
                        }
                } else {
                        echo "File does not exist";
                        write_log_error("File {$file} does not exist");
                }
        }
}

function process_file_products_stock($file, $position = 0, $retry = 0, $init_part = 0)
{
        $part = 100;
        $start = get_field('posicion_actual_stock', 'option');
        $end = $part;
        $qty_max = count_item_file($file);

        $count = 0;
        if ($position != 0) {
                $count = $position;
        }
        $init = $count;
        $itemNow = "";
        try {
                $data = read_file($file, $start, $part);
                $qty = count($data);
                $msg = "Start: {$start} / End: {$end} / qty_max: {$qty_max} / qty: {$qty}<br>";
                echo $msg;

                //Procesar la información
                for ($i = $init; $i < $qty; $i++) {
                        $count++;
                        $itemNow = $data[$i];

                        if ($count < $position) {
                                continue;
                        }
                        process_information_stock($data, $i, $count);
                }

                $start += $part;
                $end += $part;
                update_field('posicion_actual_stock', $start, 'option');

                if ($qty_max <= $start) {
                        //update_field('posicion_actual', 0, 'option');
                        update_field('corriendo_productos', true, 'option');
                        update_field('corriendo_mooring', true, 'option');
                }
        } catch (Exception $e) {
                // si ocurre algún error guardar la posicion actual
                write_log_error_item($itemNow, $retry);

                if ($retry < 3) {
                        $count--;
                        $retry++;
                } else {
                        $retry = 0;
                }
                process_file_products_stock($file, $count, $retry, $start);
                //throw $e;
        }
}

function process_information_stock($data, $i, $count)
{
        if (isset($data[$i]["Id_Item"])) {
                $ans = getProductByIdItemSITE($data[$i]["Id_Item"]);
                $product_stock = $data[$i];
                $id_item = $data[$i]["Id_Item"];

                if ($ans["httpStatus"] != 200) {
                        // Create
                        $msg = "Producto con id item: {$id_item} no existe!!!";
                        write_log_error($msg);
                } else {
                        // Update
                        $product = $ans['response'];
                        updated_product_stock($product, $product["id"], $product_stock);
                        $msg = "ACTUALIZADO EL STOCK DEL PRODUCTO: #" . $ans['response']["id"];
                        write_log_process_item($product_stock, $count, $msg);
                }
        }
}

function updated_product_stock($product, $id_product, $product_stock)
{
        $exist = false;
        $infoExtruct = array();
        $ansCeller = search_id_cellers_erp($product_stock["Id_Bodega"]);

        if (empty($ansCeller)) {
                $variations = getProductByIdSellersSITE($product_stock["Id_Bodega"]);
                if ($variations["httpStatus"] == 200) {
                        $exist = true;
                        $attribute = $variations["response"];
                        $infoExtruct["name"] = $attribute->name;
                        $infoExtruct["id_variation"] = $attribute->term_id;
                        $infoExtruct["id_celler"] = $product_stock["Id_Bodega"];
                        insert_cellers_erp(
                                $infoExtruct["id_celler"],
                                $infoExtruct["id_variation"],
                                $infoExtruct["name"]
                        );
                }
        } else {
                $ansCeller = $ansCeller[0];
                $infoExtruct["name"] = $ansCeller->name;
                $infoExtruct["id_variation"] = $ansCeller->id_variation;
                $infoExtruct["id_celler"] = $ansCeller->id_celler;
                $exist = true;
        }

        if ($exist) {
                add_product_attribute($product, $id_product, $infoExtruct["name"]);

                $dat = array(
                        "regular_price" => number_format($product_stock["Precio"], 2, '.', ''),
                        "manage_stock" => true,
                        "stock_quantity" => $product_stock["Stock"],
                        "stock_status" => "instock",
                        "tax_status" => "taxable",
                        "date_on_sale_from" => $product_stock["Fecha_Inf"],
                        "date_on_sale_to" => $product_stock["Fecha_Sup"],
                        "sale_price" => number_format($product_stock["Precio_Rebajado"], 2, '.', ''),
                        "attributes" => [
                                [
                                        "id" => 1,
                                        "name" => "Bodegas",
                                        "option" => $infoExtruct["name"]
                                ]
                        ],
                        "meta_data" => [
                                [
                                        "key" => "custom_field_variant_id_bodega",
                                        "value" => $product_stock["Id_Bodega"],
                                ],
                                [
                                        "key" => "custom_field_variant_id_item",
                                        "value" => $product_stock["Id_Item"],
                                ],
                                [
                                        "key" => "custom_field_variant_qty_max",
                                        "value" => $product_stock["Cant_Min"],
                                ],
                                [
                                        "key" => "custom_field_variant_qty_min",
                                        "value" => $product_stock["Cant_Max"],
                                ]
                        ]
                );

                $list_all_cellars = getAllProductsCellars($id_product);
                $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
                //write_log_error("ID product> ". $id_product . " =>  " . $json);
                if (!empty($list_all_cellars["response"])) {
                        $all_variations = $list_all_cellars["response"];

                        $args_list_all_cellars = array_column($all_variations, "attributes");
                        $options = array_column(array_column($args_list_all_cellars, 0), "option");
                        $key = array_search($infoExtruct["name"], $options);

                        if ($key === false) {
                                echo "BODEGA DEL PRODUCTO ID ITEM {$id_product} CREADO<br><br>";
                                $ans = createUpdateProductsStockSITE($id_product, null, $json, "POST");
                        } else {
                                echo "BODEGA DEL PRODUCTO ID ITEM {$id_product} ACTUALIZADO<br><br>";
                                $variation_id = $all_variations[$key]->id;
                                $ans = createUpdateProductsStockSITE($id_product, $variation_id, $json, "PUT");
                        }
                } else {
                        echo "BODEGA DEL PRODUCTO ID ITEM {$id_product} CREADO 2<br><br>";
                        $ans = createUpdateProductsStockSITE($id_product, null, $json, "POST");
                }
        } else {
                $msg = "<br>Variation not found!<br> " . $product_stock["Id_Bodega"];
                write_log_error($msg);
        }
}

function add_product_attribute($product, $id_product, $name_attribute)
{
        $all_attributes = $product["attributes"];
        $options = array();
        $ku = array_search("pa_bodegas", array_column($all_attributes, "name"));

        if ($ku === false) {
                array_push($options, $name_attribute);
        } else {
                $options = array_column($all_attributes, "options");
                $options = $options[$ku];
                array_push($options, $name_attribute);
        }

        $all_options = array_unique($options);

        $dat = array(
                "attributes" =>
                [
                        [
                                "id" => 1,
                                "variation" => true,
                                "options" => $all_options
                        ]
                ]
        );

        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE($id_product, $json, "PUT");
}
