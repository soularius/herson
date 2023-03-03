<?php
// Función para enviar un archivo vía FTP
function enviar_ftp($server, $username, $password, $file, $ruta_de_directorio_local)
{
    // Abrir conexión FTP
    $conn = ftp_connect($server);
    $login = ftp_login($conn, $username, $password);
    $ruta_de_directorio_externo = get_field('ruta_de_directorio_externo', 'option');

    // Verificar si se pudo conectar y loguear al servidor
    if (!$conn || !$login) {
        return false;
    }

    // Activar modo pasivo
    ftp_pasv($conn, true);

    // Subir el archivo al servidor
    ftp_put($conn, $ruta_de_directorio_externo . $file, $ruta_de_directorio_local . $file, FTP_ASCII);

    // Cerrar la conexión FTP
    ftp_close($conn);

    return true;
}

// Se usa el hook de confirmación del pago, ya que si se hace en la thank you page, al recargar la página volvía a hacer el proceso.
add_action('woocommerce_payment_complete', 'agendamiento_csv_ftp_async');

function agendamiento_csv_ftp_async($order_id)
{
    // Agendamiento del evento.
    wp_schedule_single_event(time() + 10, 'csv_ftp_async', array($order_id));
}
add_action('csv_ftp_async', 'create_csv_and_send_to_ftp', 10, 3);

function create_csv_and_send_to_ftp($order_id)
{
    // Recupera el objeto WC_Order para esta compra
    $order = wc_get_order($order_id);

    // Crea una variable para almacenar los detalles de los productos comprados
    $product_details = array();

    // Recorre cada producto en la compra
    foreach ($order->get_items() as $item) {

        $product_id = $item->get_product_id();
        $mooring = get_field("producto_amarre", $product_id);
        $id_bodega = get_field('id_bodega', 'user_' . $order->get_customer_id());

        // Validación de si es un amarre
        if ($mooring === true) {
            // Definición de nuevas variables en relación a amarres
            $all_data = get_field("producto_en_sede", $product_id);
            $sedes = array_column($all_data, "sede");

            // Definición de id del término a partir del id_bodega
            $args = array(
                'taxonomy' => 'pa_bodegas',
                'hide_empty' => false,
                'parent' => 0,
                'meta_query'    => array(
                    'relation'        => 'AND',
                    array(
                        'key'         => 'id_bodega',
                        'value'          => $id_bodega,
                        'compare'     => '=',
                    )
                )
            );
            $attributes = get_terms($args);
            $default_variation = $attributes[0]->term_taxonomy_id;
            $key = array_search($default_variation, $sedes);
            $acf_product_morning_list = $all_data[$key]["listado_de_productos"];

            // Recorriendo el amarre (Productos que lo componen)
            if ($key !== false) {
                foreach ($acf_product_morning_list as $key => $acf_product_mooring) {
                    $product_mooring = $acf_product_mooring["producto"];
                    $obj_product_mooring = wc_get_product($product_mooring->ID);

                    // Listado de las variaciones del producto, para obtener el precio de la sede correcta
                    $variations = $obj_product_mooring->get_available_variations();
                    foreach ($variations as $key_variation => $variation) {
                        if ($variation['custom_field_variant_id_bodega'] == $id_bodega) {
                            $price = $variation['display_regular_price'];
                        }
                    }

                    // Listado de productos para obtener la cantidad del amarre
                    if (have_rows('producto_en_sede', $product_id)) :
                        while (have_rows('producto_en_sede', $product_id)) : the_row();
                            if (get_row_layout() == 'amarre_sede') :
                                if (have_rows('listado_de_productos')) :
                                    while (have_rows('listado_de_productos')) : the_row();
                                        if (get_sub_field('codigo_item') == $obj_product_mooring->get_sku()) {
                                            $cantidad_del_producto = get_sub_field('cantidad');
                                        }
                                    endwhile;
                                endif;
                            endif;
                        endwhile;
                    endif;

                    // Agrega la información del producto al arreglo de detalles
                    $product_details[] = array(
                        'Codigo' => $obj_product_mooring->get_sku(),
                        'Notas_Linea' => 'Ecommerce',
                        'Cantidad' => $cantidad_del_producto,
                        'Precio_Total' => $price,
                        'Id_Cliente' => get_field('codigo', 'user_' . $order->get_customer_id()),
                        'Id_Bodega' => $id_bodega,
                        'Id_concepto' => 0,
                        'Id_Contacto' => 0,
                        'Nota_Ped' => $order_id,
                        'Cod_Vende' => '',
                        'Num_Doc' => 0,
                        'Doc_Refer' => 0,
                        'Con_Pago' => 1399
                    );
                }
            }
        } else {
            // Producto convencional - No amarre
            // Agrega la información del producto al arreglo de detalles
            $product_details[] = array(
                'Codigo' => $item->get_product()->get_sku(),
                'Notas_Linea' => 'Ecommerce',
                'Cantidad' => $item->get_quantity(),
                'Precio_Total' => $item->get_total(),
                'Id_Cliente' => get_field('codigo', 'user_' . $order->get_customer_id()),
                'Id_Bodega' => get_field('id_bodega', 'user_' . $order->get_customer_id()),
                'Id_concepto' => 0,
                'Id_Contacto' => 0,
                'Nota_Ped' => $order_id,
                'Cod_Vende' => '',
                'Num_Doc' => 0,
                'Doc_Refer' => 0,
                'Con_Pago' => 1399
            );
        }
    }

    // Crea un nuevo archivo CSV para almacenar los detalles de los productos
    $file_name = $order_id . '.txt';
    $ruta_de_directorio_local = get_field('ruta_de_directorio_local', 'option');
    $file = fopen($ruta_de_directorio_local . $file_name, 'w');

    // Agrega una fila de encabezados al archivo CSV
    //fputcsv($file, array_keys($product_details[0])); Pidieron quitar encabezados

    // Recorre el arreglo de detalles de los productos y agrega una fila al archivo CSV para cada uno
    foreach ($product_details as $product) {
        fputcsv($file, $product, ';');
    }

    // Cierra el archivo CSV
    fclose($file);

    $server = get_field('servidor_ftp', 'option');
    $username = get_field('usuario_ftp', 'option');
    $password = get_field('clave_ftp', 'option');

    // Enviar FTP
    enviar_ftp($server, $username, $password, $file_name, $ruta_de_directorio_local);
}
