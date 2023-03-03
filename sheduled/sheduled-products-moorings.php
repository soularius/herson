<?php

get_template_part('ERP/connect', 'products-moorings');

function file_product_moorings()
{
        $file = __DIR__."/../ERP/tmp/list-product-moorings.json";
        
        if (get_field('corriendo_productos', 'option') &&
            get_field('corriendo_stock', 'option') &&
            get_field('corriendo_mooring', 'option')
        ) {
                update_field('corriendo_productos', false, 'option');
                update_field('corriendo_stock', false, 'option');
                update_field('corriendo_mooring', true, 'option');
                update_field('posicion_actual_productos_mooring', 0, 'option');
                products_moorings_erp();
                clearstatcache();
                sleep(3);
                format_json($file);
        }
        
        if (get_field('corriendo_mooring', 'option')) {
            if (file_exists($file)) {
                    if (is_readable($file)) {
                            clearstatcache();
                            sleep(3);
                            process_file_products_mooring($file);
                    }else {
                            echo "File is not readable";
                            write_log_error("File {$file} is not readable");
                    }
            } else {
                    echo "File does not exist";
                    write_log_error("File {$file} does not exist");
            }
        }
}

function process_file_products_mooring($file, $position = 0, $retry = 0, $init_part = 0)
{
        $part = 100;
        $start = get_field('posicion_actual_productos_mooring', 'option');
        $end = $part;
        $qty_max = count_item_file($file);

        $count = 0;
        if ($position != 0 ) {
                $count = $position;
        }
        $init = $count;
        $itemNow = "";
        try {
                $data = read_file($file, $start, $part);
                $qty = count($data);
                $msg = "Start: {$start} / End: {$end} / qty_max: {$qty_max} / qty: {$qty}<br>";

                //Procesar la información
                for ($i = $init; $i < $qty; $i++) {
                        $count ++;
                        $itemNow = $data[$i];
                        if ($count < $position) {
                                continue;
                        }
                        process_information_mooring($data, $i, $count);
                }

                $start += $part;
                $end += $part;
                update_field('posicion_actual_productos_mooring', $start, 'option');

                if($qty_max <= $start)
                {
                        //update_field('posicion_actual', 0, 'option');
                        update_field('corriendo_productos', false, 'option');
                        update_field('corriendo_stock', false, 'option');
                        update_field('corriendo_mooring', false, 'option');
                        update_field('posicion_actual_productos_mooring', 0, 'option');
                        update_field('posicion_actual_stock', 0, 'option');
                        update_field('posicion_actual', 0, 'option');
                }
                
        } catch (Exception $e) {
                // si ocurre algún error guardar la posicion actual
                write_log_error_item($itemNow, $retry);

                if($retry < 3){
                        $count --;
                        $retry ++;
                } else {
                        $retry = 0;
                }
                process_file_products_mooring($file, $count, $retry, $start);
                //throw $e;
        }
}

function process_information_mooring($data, $i, $count)
{
        
    if (isset($data[$i]["Codigo"])) {
            $code = $data[$i]["Codigo"];
            $exist = false;
                

            $id_product = validate_register_code($data, $i, $code, $exist);
            if ($exist) {
                $moorings = $data[$i];

                $code = explode("*", $moorings["Codigo_Item_Combo"]);
                $code_combo = $code[count($code) - 1];
                $bonif = $code_combo == "PB" ? true : false;
                $dat = array(
                    "id" => $id_product,
                    "sede" => $moorings["Id_Bodega"],
                    "listado_de_productos" => array(
                        array(
                            "codigo_item" => $moorings["Codigo_Item_Combo"],
                            "descripcion_item_amarre" => $moorings["Descripcion"],
                            "cantidad" => $moorings["Cantidad"],
                            "precio_item" => $moorings["Precio"],
                            "producto_bonificado" => $bonif
                        )
                    )
                );
                
                $ans = postProductMooringsSITE(json_encode($dat));

                if($ans["httpStatus"] != 200) {
                    write_log_error(json_encode($ans));
                } else {
                    $msg = "ACTUALIZADO EL STOCK DEL PRODUCTO: #".$id_product;
                    write_log_error($msg);
                }
            } else {
                write_log_error("product no exist " . $code);
            }
    }
}


function validate_register_code($data, $i, $code, &$exist = false)
{
        $res = search_code_products_erp($code);
        $id_product = null;
        if(empty($res)) {
                $ans = getProductBySkuItemSITE($data[$i]["Codigo"]);
                if ($ans["httpStatus"] == 200) {
                        $exist = true;
                        $id_product = $ans['response']["id"];
                        $sku = $ans['response']["sku"];
                        $meta_data = $ans['response']["meta_data"];
                        $keyS = array_search("id_item", array_column($meta_data, "key"));
                        $id_item = $meta_data[$keyS]["value"];
                        insert_products_erp($id_item, $id_product, $sku);
                }
        } else {
                $id_product = $res[0]->id_product;
                $exist = true;
        }
        return $id_product;
}