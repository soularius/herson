<?php

include __DIR__ . "/../assets/vendor/autoload.php";
get_template_part('ERP/connect', 'products_2');
get_template_part('ERP/connect', 'products-stock_2');
get_template_part('SITE/connect', 'products-stock_0');
get_template_part('SITE/connect', 'cellars-variations_0');
get_template_part('SITE/connect', 'products_0');
get_template_part('SQL/sql', 'products-erp');
get_template_part('SQL/sql', 'cellers-erp');

function process_products()
{
        file_product_2();
}

function file_product_2()
{
        $file = __DIR__."/../ERP/tmp/list-product2.json";
        if(!get_field('corriendo_productos_copiar', 'option') && !get_field('corriendo_stock_copiar', 'option')) {
                update_field('corriendo_productos_copiar', true, 'option');
                products_erp_2();
                clearstatcache();
                sleep(3);
                format_json_2($file);
        }

        if(get_field('corriendo_productos_copiar', 'option')) {

                if (file_exists($file)) {
                        if (is_readable($file)) {
                                process_file_products_2($file);
                        }else {
                                echo "File is not readable";
                                write_log_error_2("File {$file} is not readable");
                        }
                } else {
                        echo "File does not exist";
                        write_log_error_2("File {$file} does not exist");
                }
        }

        if(get_field('corriendo_stock_copiar', 'option')) {
                file_product_stock_2();
        }
        
}

function process_file_products_2($file, $position = 0, $retry = 0, $init_part = 0)
{
    $part = 100;
    $start = get_field('posicion_actual_copiar', 'option');
    $end = $part;
    $qty_max = count_item_file_2($file);

    $count = 0;
    if ($position != 0 ) {
            $count = $position;
    }
    $init = $count;
    $itemNow = "";

        try {
                $data = read_file_2($file, $start, $part);
                $qty = count($data);
                echo "Start: {$start} / End: {$end} / qty_max: {$qty_max} / qty: {$qty}<br>";
                //Procesar la información
                for ($i = $init; $i < $qty; $i++) {
                        $count ++;
                        $itemNow = $data[$i];
                
                        if ($count < $position) {
                                continue;
                        }
                        process_information_2($data, $i, $count);
                }

                $start += $part;
                $end += $part;
                update_field('posicion_actual_copiar', $start, 'option');

                if($qty_max <= $start)
                {
                        //update_field('posicion_actual_copiar', 0, 'option');
                        update_field('corriendo_stock_copiar', true, 'option');
                }
                
        } catch (Exception $e) {
                // si ocurre algún error guardar la posicion actual
                write_log_error_2_item($itemNow, $retry);

                if($retry < 3){
                        $count --;
                        $retry ++;
                } else {
                        $retry = 0;
                }
                process_file_products_2($file, $count, $retry, $start);
                //throw $e;
        }
}

function process_information_2($data, $i, $count)
{
        if (isset($data[$i]["Id_Item"])) {
                $id_item = $data[$i]["Id_Item"];
                $exist = false;

                $id_product = validate_register_0($data, $i, $id_item, $exist);

                $product = $data[$i];
                if (!$exist) {
                        // Create
                        create_product_2($product);
                        write_log_process_item_2($product, $count, "CREADO UN PRODUCTO");
                } else {
                        // Update
                        updated_product_2($id_product, $product);
                        write_log_process_item_2($product, $count, "ACTUALIZADO UN PRODUCTO");
                }
        }
}

function validate_register_0($data, $i, $id_item, &$exist = false)
{
        $res = search_id_products_erp($id_item);
        $id_product = null;
        if(empty($res)) {
                $ans = getProductByIdItemSITE_0($data[$i]["Id_Item"]);
                if ($ans["httpStatus"] == 200) {
                        $exist = true;
                        $id_product = $ans['response']["id"];
                        insert_products_erp($id_item, $id_product);
                }
        } else {
                $id_product = $res[0]->id_product;
                $exist = true;
        }
        return $id_product;
}

function create_product_2($product)
{
        $dat = array(
                "name" => $product["Nombre"],
                "sku" => $product["Codigo"],
                "tax_status" => $product["Tax_Status"] == 1 ? "taxable" : "none",
                "tax_class" => strval(intval($product["Iva"])),
                "type" => "variable",
                "description" => $product["Descripcion"],
                "short_description" => $product["Desc_Corta"],
                "meta_data" => [
                        [
                                "key" => "id_item",
                                "value" => $product["Id_Item"],
                        ]
                ]
            );
        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE_0(null, $json, "POST");
}

function updated_product_2($id, $product)
{
        $dat = array(
                "name" => $product["Nombre"],
                "sku" => $product["Codigo"],
                "tax_status" => $product["Tax_Status"] == 1 ? "taxable" : "none",
                "tax_class" => strval(intval($product["Iva"])),
                "type" => "variable",
                "description" => $product["Descripcion"],
                "short_description" => $product["Desc_Corta"],
                "meta_data" => [
                        [
                                "key" => "id_item",
                                "value" => $product["Id_Item"],
                        ]
                ]
            );

        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE_0($id, $json, "PUT");
}

function file_product_stock_2()
{
        $file = __DIR__."/../ERP/tmp/list-product-stock2.json";
        
        if(get_field('corriendo_productos_copiar', 'option') && get_field('corriendo_stock_copiar', 'option')) {
                update_field('corriendo_productos_copiar', false, 'option');
                update_field('corriendo_stock_copiar', true, 'option');
                update_field('posicion_actual_stock_copiar', 0, 'option');
                products_stock_erp_2();
                clearstatcache();
                sleep(3);
                format_json_2($file);
        }
        
        if(get_field('corriendo_stock_copiar', 'option')) {
        
                if (file_exists($file)) {
                        if (is_readable($file)) {
                                clearstatcache();
                                sleep(3);
                                process_file_products_stock_2($file);
                        }else {
                                echo "File is not readable";
                                write_log_error_2("File {$file} is not readable");
                        }
                } else {
                        echo "File does not exist";
                        write_log_error_2("File {$file} does not exist");
                }
        }
}

function process_file_products_stock_2($file, $position = 0, $retry = 0, $init_part = 0)
{
        $part = 100;
        $start = get_field('posicion_actual_stock_copiar', 'option');
        $end = $part;
        $qty_max = count_item_file_2($file);

        $count = 0;
        if ($position != 0 ) {
                $count = $position;
        }
        $init = $count;
        $itemNow = "";
        try {
                $data = read_file_2($file, $start, $part);
                $qty = count($data);
                echo "Start: {$start} / End: {$end} / qty_max: {$qty_max} / qty: {$qty}<br>";

                //Procesar la información
                for ($i = $init; $i < $qty; $i++) {
                        $count ++;
                        $itemNow = $data[$i];

                        /*  if ($count == 50) {
                                die("FINALIZADO");
                                throw new Exception("Error intencional en el elemento " . $count);
                        } */

                        if ($count < $position) {
                                continue;
                        }
                        process_information_2_stock_2($data, $i, $count);
                }

                $start += $part;
                $end += $part;
                update_field('posicion_actual_stock_copiar', $start, 'option');
                

                if($qty_max <= $start)
                {
                        update_field('posicion_actual_stock_copiar', 0, 'option');
                        update_field('posicion_actual_copiar', 0, 'option');
                        update_field('corriendo_stock_copiar', false, 'option');
                        update_field('corriendo_productos_copiar', false, 'option');
                }
        } catch (Exception $e) {
                // si ocurre algún error guardar la posicion actual
                write_log_error_2_item($itemNow, $retry);

                if($retry < 3){
                        $count --;
                        $retry ++;
                } else {
                        $retry = 0;
                }
                process_file_products_stock_2($file, $count, $retry, $start);
                //throw $e;
        }
}

function process_information_2_stock_2($data, $i, $count)
{
        if (isset($data[$i]["Id_Item"])) {
            $ans = getProductByIdItemSITE_0($data[$i]["Id_Item"]);
            $product_stock = $data[$i];
            $id_item = $data[$i]["Id_Item"];

            if ($ans["httpStatus"] != 200) {
                    // Create
                    $msg = "Producto con id item: {$id_item} no existe!!!";
                    //echo "<br>----- {$msg} ------<br>";
                    write_log_error_2($msg);
            } else {
                    // Update
                $msg = "ACTUALIZADO EL STOCK DEL PRODUCTO: #". $ans['response']["id"];
                $product = $ans['response'];
                updated_product_2_stock_2($product, $product["id"], $product_stock);
                write_log_process_item_2($product_stock, $count, $msg);
            }
        }
}

function updated_product_2_stock_2($product, $id_product, $product_stock)
{
        $exist = false;
        $infoExtruct = array();
        $ansCeller = search_id_cellers_erp($product_stock["Id_Bodega"]);

        if (empty($ansCeller)) {
                $variations = getProductByIdSellersSITE_0($product_stock["Id_Bodega"]);
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
                add_product_attribute_2($product, $id_product, $infoExtruct["name"]);

                $dat = array(
                        "price" => $product_stock["Precio"],
                        "manage_stock" => true,
                        "stock_quantity" => $product_stock["Stock"],
                        "stock_status" => "instock",
                        "tax_status" => "taxable",
                        "attributes"=> [
                          [
                            "id" => 1,
                            "option"=> $infoExtruct["name"]
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
                                ]
                        ]
                    );
                $list_all_cellars = getAllProductsCellars_0($id_product);
                $json = json_encode($dat, JSON_UNESCAPED_UNICODE);

                if (!empty($list_all_cellars["response"])) {
                        $all_variations = $list_all_cellars["response"];
                        $args_list_all_cellars = array_column($all_variations, "attributes");
                        $options = array_column(array_column($args_list_all_cellars, 0), "option");
                        $key = array_search($attribute->name, $options);

                        if ($key === false) {
                                //echo "BODEGA DEL PRODUCTO ID ITEM {$id_product} CREADO";
                                $ans = createUpdateProductsStockSITE_0($id_product, null, $json, "POST");
                        } else {
                                //echo "BODEGA DEL PRODUCTO ID ITEM {$id_product} ACTUALIZADO";
                                $variation_id = $all_variations[$key]->id;
                                $ans = createUpdateProductsStockSITE_0($id_product, $variation_id, $json, "PUT");
                        }
                } else {
                        $ans = createUpdateProductsStockSITE_0($id_product, null, $json, "POST");
                }
        } else {
                echo "<br>Variation not found!<br> ".$product_stock["Id_Bodega"];
        }
}


function add_product_attribute_2($product, $id_product, $name_attribute)
{
        $all_attributes = $product["attributes"];
        $options = array();
        $ku = array_search("pa_bodegas", array_column($all_attributes, "name"));

        if ($ku === false) {
                array_push($options, $name_attribute);
                write_log_error("ku no existe");
        } else {
                $options = array_column($all_attributes, "options");
                $options = $options[$ku];
                array_push($options, $name_attribute);
                $all_options = array_unique($options);
                write_log_error("ku existe");
        }

        $dat = array( "attributes" =>
                        [
                                [
                                        "id" => 1,
                                        "variation" => true,
                                        "options" => $all_options
                                ]
                        ]
                );

        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE_0($id_product, $json, "PUT");
}

function read_file_2($file, $start, $part)
{
        // Crear una instancia de la clase InMemoryListener para manejar los datos del archivo
        $listener = new \JsonStreamingParser\Listener\InMemoryListener();
    
        $stream = fopen($file, 'r');
        try {
                $parser = new \JsonStreamingParser\Parser($stream, $listener);
                $parser->parse();
                $all_obj = $listener->getJson();
                $fragment = array_slice($all_obj, $start, $part);
                fclose($stream);
                return $fragment;
        } catch (Exception $e) {
        fclose($stream);
                throw $e;
        }
        return null;
}

function count_item_file_2($file)
{
        $listener = new \JsonStreamingParser\Listener\InMemoryListener();
        $stream = fopen($file, 'r');
        $parser = new \JsonStreamingParser\Parser($stream, $listener);
        $parser->parse();
        fclose($stream);
        return count($listener->getJson());
}

function format_json_2($file)
{
    $json = file_get_contents($file);
    $json = utf8_encode($json);
    $data = json_decode($json, true);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $json);
}

function write_log_error_2($msg)
{
    $logfile = __DIR__."/../ERP/tmp/log_2.json";

    $log = fopen($logfile, 'a');
    $msg = date('Y-m-d H:i:s') .
            " -> {$msg}:";
    fwrite($log, $msg . PHP_EOL);
    fclose($log);
}

function write_log_error_2_item($item, $retry)
{
    $logfile = __DIR__."/../ERP/tmp/log_2.json";
    $id_item = $item["Id_Item"];

    $log = fopen($logfile, 'a');
    $strRetry = $retry > 10 ? '0'.$retry : $retry;
    $msg = date('Y-m-d H:i:s') .
            " -> Erro al procesar el producto con ID #{$id_item} En el Reinteno #{$strRetry}:";
    fwrite($log, $msg . PHP_EOL);
    fwrite($log, json_encode($item, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL);
    fclose($log);
}

function write_log_process_item_2($item, $pos, $process)
{
        $logfile = __DIR__."/../ERP/tmp/log_process_2.json";
        $id_item = $item["Id_Item"];

        $log = fopen($logfile, 'a');

        $msg = "<br>------- SE HA: {$process} ----------<br>";
        fwrite($log, $msg . PHP_EOL);
        $msg = date('Y-m-d H:i:s') .
                " -> Procesado el producto con ID #{$id_item} En la posicion #{$pos}:";
        fwrite($log, $msg . PHP_EOL);
        fwrite($log, json_encode($item, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL);
        fclose($log);
}