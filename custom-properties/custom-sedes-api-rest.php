<?php

add_filter('woocommerce_rest_prepare_pa_bodegas', 'custom_products_api_data', 90, 3);
function custom_products_api_data($response, $post, $request)
{
	$params = $request->get_params();
    if (isset($params['id_bodega'])) {
	    update_field('id_bodega', $params['id_bodega'], 'pa_bodegas' . '_' . $response->data['id']);
    }
    
    if (isset($params['direccion_de_la_agencia'])) {
	    update_field('direccion_de_la_agencia', $params['direccion_de_la_agencia'], 'pa_bodegas' . '_' . $response->data['id']);
    }

    if (isset($params['numero_telefonico_de_la_agencia'])) {
	    update_field('numero_telefonico_de_la_agencia', $params['numero_telefonico_de_la_agencia'], 'pa_bodegas' . '_' . $response->data['id']);
    }
	
	// retrieve a custom field and add it to API response
	$response->data['id_bodega'] = get_field('id_bodega', 'pa_bodegas' . '_' . $response->data['id']);
	$response->data['direccion_de_la_agencia'] = get_field('direccion_de_la_agencia', 'pa_bodegas' . '_' . $response->data['id']);
	$response->data['numero_telefonico_de_la_agencia'] = get_field('numero_telefonico_de_la_agencia', 'pa_bodegas' . '_' . $response->data['id']);

	return $response;
}