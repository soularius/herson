<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );
/**
 * Definición Widget artículos destacados
 */
get_template_part('template-parts/widgets', 'customs');
get_template_part('template-parts/widgets', 'load-page');
get_template_part('template-parts/settings', 'footer');
get_template_part('template-parts/myaccount/init');
get_template_part('template-parts/custom', 'dashboard');
get_template_part('template-parts/custom', 'sendEmailEncuesta');
get_template_part('custom-properties/custom', 'acf-by-variant-product');
get_template_part('custom-properties/custom', 'validation-max-qty');
get_template_part('custom-properties/custom', 'quantity-buttons');
get_template_part('custom-properties/custom', 'validation-min-order');
get_template_part('custom-properties/custom', 'validation-relation-user-shop');
get_template_part('custom-properties/custom', 'states');
get_template_part('custom-properties/custom', 'sedes-api-rest');
get_template_part('custom-properties/custom', 'products-api-rest');
get_template_part('custom-properties/custom', 'category-api-rest');
get_template_part('custom-properties/custom', 'attributes-cellars-api-rest');
get_template_part('custom-properties/custom', 'filter-sku-search-mooring');
get_template_part('custom-properties/custom', 'add-information-mooring');
get_template_part('template-parts/custom', 'add-menu-my-account');
get_template_part('sheduled/sheduled', 'sedes');
get_template_part('sheduled/sheduled', 'products2');

// Creación del CSV de los productos de la orden y envío vía FTP
get_template_part('woocommerce/checkout/create-csv-send-ftp');

// get_template_part('template-parts/calculadora/init');

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style()
{
	wp_dequeue_style('storefront-style');
	wp_dequeue_style('storefront-woocommerce-style');
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

// Footer Logged/No Logged
function content_to_logged_footer()
{
	if (is_user_logged_in()) {
		require_once "template-parts/footer-logged.php";
	} else {
		require_once "template-parts/footer-no-logged.php";
	}
}
add_action('customFooter', 'content_to_logged_footer');
// Fin Footer Logged/No Logged

// Home Logged/No Logged
function content_to_logged_home()
{
	if (is_user_logged_in()) {
		require_once "template-parts/inicio-logged.php";
	} else {
		require_once "template-parts/inicio-no-logged.php";
	}
}
add_action('customHeader', 'content_to_logged_home');
// Fin Footer Logged/No Logged

// Adicionar/Eliminar Opciones de Logueo al Header
add_action('init', 'delay_remove');
function delay_remove()
{
	remove_action('storefront_header', 'storefront_secondary_navigation', 30);
	// remove_action('storefront_header', 'storefront_site_branding', 20);
	// remove_action('storefront_header', 'storefront_primary_navigation', 50);
	remove_action('storefront_header', 'storefront_header_cart', 60);
	remove_action('storefront_header', 'storefront_product_search', 40);
	// remove_action('storefront_header', 'storefront_product_search', 40);
	// add_action('storefront_header', 'storefront_site_branding', 50);
	// add_action('storefront_header', 'logo_fixed_header', 50);
	// add_action('storefront_header', 'storefront_primary_navigation_coper', 50);
	add_action('storefront_header', 'menuLogged', 20);
	add_action('storefront_header', 'menuWishList', 30);
	if (is_user_logged_in()) {
		add_action('storefront_header', 'storefront_header_cart', 20);
		add_action('storefront_header', 'storefront_product_search', 40);
	} else {
	}
}

function menuLogged()
{
	if (is_user_logged_in()) {
		wp_nav_menu(array(
			'theme_location' => 'menu-top-users-logged',
			'container_class' => 'custom-top-menu-user'
		));
	} else {
		wp_nav_menu(array(
			'theme_location' => 'menu-top-users',
			'container_class' => 'custom-top-menu-user'
		));
	}
}

function menuWishList()
{
	if (is_user_logged_in()) {
		wp_nav_menu(array(
			'theme_location' => 'menu-top-favoritos-logged',
			'container_class' => 'custom-top-favoritos-logged'
		));
	}
}

/* Remove "in stock" text form single products */
function remove_in_stock_text_form_single_products($html, $text, $product)
{
	$availability = $product->get_availability();
	if (isset($availability['class']) && 'in-stock' === $availability['class']) {
		return '';
	}
	return $html;
}
add_filter('woocommerce_stock_html', 'remove_in_stock_text_form_single_products', 10, 3);


// add_action('woocommerce_order_status_changed', 'woo_order_status_change_custom', 10, 3);

// function woo_order_status_change_custom($order_id,$old_status,$new_status) {
// $to = 'jairo.alonso.alvarez.velasquez@opperweb.com';
// $subject = 'The subject';
// $body = 'The email body content';
// $headers = array('Content-Type: text/html; charset=UTF-8');

// wp_mail( $to, $subject, $body, $headers );

// }

add_action('init', 'brandpage_form_head');
function brandpage_form_head()
{
	acf_form_head();
}

add_filter('acf/prepare_field/name=checkPoll', 'my_prepare_field');
function my_prepare_field($field)
{

	// hide on front-end
	if (!is_admin()) {
		return false;
	}

	// return normally
	return $field;
}

add_filter('shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3);

function custom_shortcode_atts_wpcf7_filter($out, $pairs, $atts)
{
	$my_attr = 'destination-email';

	if (isset($atts[$my_attr])) {
		$out[$my_attr] = $atts[$my_attr];
	}

	return $out;
}


add_action('pa_bodegas_add_form_fields', 'rudr_add_term_fields');

function rudr_add_term_fields($taxonomy)
{
?>
	<tr class="form-field">
		<th>
			<label for="term_priority"><?php echo esc_html__('Id Bodega', 'etoiles'); ?></label>
		</th>
		<td>
			<input name="term_priority" id="term_priority" type="number" value="" />
		</td>
	</tr>
<?php
}

add_action('pa_bodegas_edit_form_fields', 'etoiles_add_priority_field', 10, 2);

function etoiles_add_priority_field($term, $taxonomy)
{
	$term_priority = get_term_meta($term->term_id, 'term_priority', true);
?>
	<tr class="form-field">
		<th>
			<label for="term_priority"><?php echo esc_html__('Id Bodega', 'etoiles'); ?></label>
		</th>
		<td>
			<input name="term_priority" id="term_priority" type="number" value="<?php echo esc_attr($term_priority) ?>" />
		</td>
	</tr>
<?php
}

add_action('created_pa_bodegas', 'rudr_save_term_fields');
add_action('edited_pa_bodegas', 'rudr_save_term_fields');
function rudr_save_term_fields($term_id)
{
	update_term_meta(
		$term_id,
		'term_priority',
		absint($_POST['term_priority'])
	);
}

/*add_action( 'woocommerce_rest_prepare_product_attribute_term', 'update_term_attribute_product', 10, 3 );

function update_term_attribute_product( $response, $term, $request ) {
  	// Obtener los datos de la solicitud
  	$data_response = $request->get_json_params();

	// Modifica la respuesta para incluir los datos que quieres actualizar
	$response->data['id_bodega'] = $data_response['id_bodega'];
	$response->data['direccion_de_la_agencia'] = $data_response['direccion_de_la_agencia'];
	$response->data['numero_telefonico_de_la_agencia'] = $data_response['numero_telefonico_de_la_agencia'];

	// Devuelve la respuesta modificada
	return $response;
}*/

