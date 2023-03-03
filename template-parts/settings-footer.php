<?php
// Footer Logged/No Logged
function custom_style_footer()
{
	wp_enqueue_script('AOS', get_stylesheet_directory_uri() . '/assets/aos-master/dist/aos.js', array('jquery'), '1', true);
	wp_enqueue_script('herson-popper-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap/popper.min.js', array('jquery'));
	wp_enqueue_script('herson-bootstrap-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap/bootstrap.min.js', array('jquery'));
	wp_enqueue_script('herson-lightslider-aliados-js', get_stylesheet_directory_uri() . '/assets/js/lightslider/lightslider-aliados.js', array('jquery'), '', true);
	wp_enqueue_script('herson-lightslider-quickOptions-js', get_stylesheet_directory_uri() . '/assets/js/lightslider/lightslider-quickoptions.js', array('jquery'), '', true);
	wp_enqueue_script('herson-lightslider-testimonios-js', get_stylesheet_directory_uri() . '/assets/js/lightslider/lightslider-testimonios.js', array('jquery'), '', true);
	wp_enqueue_script('herson-lightslider-js', get_stylesheet_directory_uri() . '/assets/js/lightslider/lightslider.js', array('jquery'), '', true);
	wp_enqueue_script('herson-script-js', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery', 'AOS', 'herson-lightslider-js'), '', true);
	// wp_enqueue_script('herson-sticky-sidebar-js', get_stylesheet_directory_uri() . '/assets/js/sidebar/sticky-sidebar.js', array('jquery'));
	wp_enqueue_script('herson-jquery-sticky-sidebar--js', get_stylesheet_directory_uri() . '/assets/js/sidebar/ResizeSensor.js', array('jquery'), '', true);
	wp_enqueue_script('herson-jquery-sticky-sidebar--js', get_stylesheet_directory_uri() . '/assets/js/sidebar/sticky-sidebar.js', array('jquery'), '', true);
}
add_action('customFooter', 'custom_style_footer');
// Fin Footer Logged/No Logged

// Visualiza productos de la categoría "Nuevos"
add_action('show_news_category', 'news_category_list');
function news_category_list()
{
	$term = search_bodega();
	$term = $term[0];
	echo do_shortcode("[products limit='8' category='nuevos' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
}

// Visualiza productos de la categoría "Ofertas"
add_action('show_oferts_category', 'oferts_category_list');
function oferts_category_list()
{
	$term = search_bodega();
	$term = $term[0];
	echo do_shortcode("[products limit='8' category='ofertas' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
}

// Visualiza productos de la categoría "Descuentos"
add_action('show_discounts_category', 'discounts_category_list');
function discounts_category_list()
{
	$term = search_bodega();
	$term = $term[0];
	echo do_shortcode("[products limit='8' category='descuentos' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
}

// Visualiza productos de la categoría "Descuentos"
add_action('show_featured_category', 'featured_category_list');
function featured_category_list()
{
	$term = search_bodega();
	$term = $term[0];
	echo do_shortcode("[products limit='8' category='destacados' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
}

// Visualiza productos de la categoría "Descuentos"
add_action('show_best_selling_category', 'best_selling_category_list');
function best_selling_category_list()
{
	$term = search_bodega();
	$term = $term[0];
	echo do_shortcode("[products limit='8' category='mas-vendidos' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
}

// Visualiza Carrito de compras
add_action('carrito_page', 'carrito_show');
function carrito_show()
{
	echo do_shortcode("[woocommerce_cart]");
}

// Visualiza Página de Mi cuenta
add_action('mi_cuenta_page', 'mi_cuenta_show');
function mi_cuenta_show()
{
	echo do_shortcode("[woocommerce_my_account]");
}

// Visualiza Página de Facturación
add_action('facturacion_page', 'facturacion_show');
function facturacion_show()
{
	echo do_shortcode("[woocommerce_checkout]");
}

// Visualiza Página de FacturaciónLaboratorios
add_action('laboratorios_page', 'laboratorios_show');
function laboratorios_show()
{
	echo do_shortcode("[products]");
}
