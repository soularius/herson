<?php

include __DIR__ . "/../assets/vendor/autoload.php";
get_template_part('sheduled/utils', 'helps');
get_template_part('sheduled/sheduled', 'products-stock');
get_template_part('sheduled/sheduled', 'products-moorings');
get_template_part('ERP/connect', 'products');
get_template_part('SITE/connect', 'products');
get_template_part('SITE/connect', 'category');
get_template_part('SQL/sql', 'products-erp');

function process_products()
{
        //file_product();
        //file_product_stock();
        //file_product_moorings();
}

// Register custom schedule interval
add_filter('cron_schedules', 'add_fifteen_minutes_interval');
function add_fifteen_minutes_interval($schedules)
{
        $schedules['fifteen_minutes'] = array(
                'interval' => 15 * MINUTE_IN_SECONDS,
                'display'  => __('Cada 15 minutos'),
        );

        $schedules['ten_minutes'] = array(
                'interval' => 10 * MINUTE_IN_SECONDS,
                'display'  => __('Cada 10 minutos'),
        );

        $schedules['eight_minutes'] = array(
                'interval' => 8 * MINUTE_IN_SECONDS,
                'display'  => __('Cada ocho minutos'),
        );

        $schedules['five_minutes'] = array(
                'interval' => 5 * MINUTE_IN_SECONDS,
                'display'  => __('Cada 5 minutos'),
        );
        return $schedules;
}

// Schedule the event to run on specific page
add_action('wp', 'schedule_products_sync_on_specific_page');
function schedule_products_sync_on_specific_page()
{
        if (is_page(36740) && current_user_can('administrator')) {
                schedule_products_sync();
        }
}

// Schedule the event to run every 15 minutes
function schedule_products_sync()
{
        if (!wp_next_scheduled('syncProductsByERP')) {
                wp_schedule_event(time(), 'eight_minutes', 'syncProductsByERP');
        }
}

// Perform the task
add_action('syncProductsByERP', 'file_product', 10, 0);

function unregister_syncProductsByERP_event()
{
        wp_clear_scheduled_hook('syncProductsByERP');
}

register_deactivation_hook(__FILE__, 'unregister_syncProductsByERP_event');

function file_product()
{
        $file = __DIR__ . "/../ERP/tmp/list-product.json";
        if (!get_field('corriendo_productos', 'option') && !get_field('corriendo_stock', 'option') && !get_field('corriendo_mooring', 'option')) {
                emptyFolder(__DIR__ . "/../ERP/tmp");
                update_field('corriendo_productos', true, 'option');
                products_erp();
                clearstatcache();
                sleep(3);
                format_json($file);
        }

        if (get_field('corriendo_productos', 'option')) {

                if (file_exists($file)) {
                        if (is_readable($file)) {
                                process_file_products($file);
                        } else {
                                echo "File is not readable";
                                write_log_error("File {$file} is not readable");
                        }
                } else {
                        echo "File does not exist";
                        write_log_error("File {$file} does not exist");
                }
        }

        if (get_field('corriendo_stock', 'option')) {
                file_product_stock();
        }

        if (get_field('corriendo_mooring', 'option')) {
                file_product_moorings();
        }
}

function process_file_products($file, $position = 0, $retry = 0, $init_part = 0)
{
        $part = 100;
        $start = get_field('posicion_actual', 'option');
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
                echo "Start: {$start} / End: {$end} / qty_max: {$qty_max} / qty: {$qty}<br>";

                $id_lab = 46;
                $get_all_lab = getCategoryProductsSITE($id_lab);

                $id_marca = 33;
                $get_all_marca = getCategoryProductsSITE($id_marca);

                $get_all_cat = getAllCategoryProductsSITE();

                $status_lab = $get_all_lab['httpStatus'];
                if ($status_lab != 200) {
                        return;
                }

                $status_marca = $get_all_marca['httpStatus'];
                if ($status_marca != 200) {
                        return;
                }
                $get_cat_lab = $get_all_lab["response"]["category"]["subcategories"];
                $get_cat_marca = $get_all_marca["response"]["category"]["subcategories"];

                //Procesar la información
                for ($i = $init; $i < $qty; $i++) {
                        $count++;
                        $itemNow = $data[$i];

                        if ($count < $position) {
                                continue;
                        }
                        process_information($data, $i, $count, $get_cat_lab, $get_cat_marca, $get_all_cat);
                }

                $start += $part;
                $end += $part;
                update_field('posicion_actual', $start, 'option');

                if ($qty_max <= $start) {
                        //update_field('posicion_actual', 0, 'option');
                        update_field('corriendo_stock', true, 'option');
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
                process_file_products($file, $count, $retry, $start);
                //throw $e;
        }
}

function process_information($data, $i, $count, $get_all_lab, $get_all_marca, $get_all_cat)
{
        if (isset($data[$i]["Id_Item"])) {
                //$list_all_sell = 
                $id_item = $data[$i]["Id_Item"];
                $exist = false;
                $id_product = validate_register($data, $i, $id_item, $exist);
                $term_lab_id = null;
                $term_mar_id = null;
                $attr = [];

                if (isset($data[$i]["Laboratorio"]) && !empty($data[$i]["Laboratorio"])) {
                        $lab_id = $data[$i]["Laboratorio"][0]["Id_Laboratorio"];
                        $list_id = array_column($get_all_lab, "id_item");
                        $labs_key = array_search($lab_id, $list_id);
                        $term_lab_id = $get_all_lab[$labs_key]["term_id"];
                }

                if (isset($data[$i]["Marca"]) && !empty($data[$i]["Marca"])) {
                        $mar_id = $data[$i]["Marca"][0]["Id_Marca"];
                        $list_id = array_column($get_all_marca, "id_item");
                        $mar_key = array_search($mar_id, $list_id);
                        $term_mar_id = $get_all_marca[$mar_key]["term_id"];
                }

                if (isset($data[$i]["Atributos"]) && !empty($data[$i]["Atributos"])) {
                        $attributes = $data[$i]["Atributos"];
                        $attr["cum"] = isset($attributes[0]["Value"]) ? $attributes[0]["Value"] : "";
                        $attr["registro_sanitario"] = isset($attributes[1]["Value"]) ? $attributes[1]["Value"] : "";
                        $attr["codigo_de_barras"] = isset($attributes[2]["Value"]) ? $attributes[2]["Value"] : "";
                }

                $product = $data[$i];
                echo "<pre>";
                var_dump($product["Nombre"]);
                echo "</pre>";
                if (!$exist) {
                        // Create
                        create_product($product, $term_lab_id, $term_mar_id, $attr);
                        write_log_process_item($product, $count, "CREADO UN PRODUCTO");
                } else {
                        // Update
                        updated_product($id_product, $product, $term_lab_id, $term_mar_id, $attr);
                        write_log_process_item($product, $count, "ACTUALIZADO UN PRODUCTO");
                }
        }
}

function validate_register($data, $i, $id_item, &$exist = false)
{
        $res = search_id_products_erp($id_item);
        $id_product = null;
        if (empty($res)) {
                $ans = getProductByIdItemSITE($data[$i]["Id_Item"]);
                if ($ans["httpStatus"] == 200) {
                        $exist = true;
                        $id_product = $ans['response']["id"];
                        $sku = $ans['response']["sku"];
                        insert_products_erp($id_item, $id_product, $sku);
                }
        } else {
                $id_product = $res[0]->id_product;
                $exist = true;
        }
        return $id_product;
}

function create_product($product, $term_lab_id, $term_mar_id, $attr)
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

        if (!is_null($term_lab_id)) {
                if (!isset($dat["categories"])) {
                        $dat["categories"] = [];
                }
                array_push($dat["categories"], ["id" => $term_lab_id]);
        }

        if (!is_null($term_mar_id)) {
                if (!isset($dat["categories"])) {
                        $dat["categories"] = [];
                }
                array_push($dat["categories"], ["id" => $term_mar_id]);
        }

        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE(null, $json, "POST");
        $id_product = $ans["response"]["id"];
        update_acf_attr($id_product, $attr);
}

function updated_product($id, $product, $term_lab_id, $term_mar_id, $attr)
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
                ],
                "images" => bucle_img($product)
        );

        if (!is_null($term_lab_id)) {
                if (!isset($dat["categories"])) {
                        $dat["categories"] = [];
                }
                array_push($dat["categories"], ["id" => $term_lab_id]);
        }

        if (!is_null($term_mar_id)) {
                if (!isset($dat["categories"])) {
                        $dat["categories"] = [];
                }
                array_push($dat["categories"], ["id" => $term_mar_id]);
        }

        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $ans = createUpdateProductsSITE($id, $json, "PUT");
        update_acf_attr($id, $attr);
}

function update_acf_attr($id_product, $attr)
{
        $dat = array(
                "product_id" => $id_product,
                "cum" => $attr["cum"],
                "registro_sanitario" => $attr["registro_sanitario"],
                "codigo_de_barras" => $attr["codigo_de_barras"]
        );
        $json = json_encode($dat, JSON_UNESCAPED_UNICODE);
        $res = updateAttributesProductsSITE($json);
}

function bucle_img($product)
{
        $img_products = [];
        foreach ($product["Images"] as $key => $img) {
                if (isset($img["Url"]) && !empty($img["Url"]) && verify_img($img["Url"])) {
                        array_push($img_products, ["src" => $img["Url"]]);
                }
        }
        return $img_products;
}

function verify_img($url)
{
        try {
                $headers = get_headers($url);
                $status = substr($headers[0], 9, 3);
                if ($status == 404 || $status == "404") {
                        return false;
                }
        } catch (Exception $e) {
                return false;
        }

        return true;
}

function emptyFolder($folderPath)
{
        // Obtiene la lista de archivos de la carpeta
        $files = scandir($folderPath);

        // Itera sobre cada archivo y lo elimina
        foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                        unlink($folderPath . '/' . $file);
                }
        }
}
