<?php
/* INICIO Herson */

//Get template for laboratories template - AJAX
get_template_part( 'template-parts/laboratories/init');

function custom_enqueue_styles_herson()
{	
	wp_enqueue_style(
		'css-style',
		get_stylesheet_directory_uri() . '/assets/css/style.css',		
		array(),
		wp_get_theme()->get('Version')
	);

    wp_enqueue_style(
		'herson-css-fonts',
		get_stylesheet_directory_uri() . '/assets/css/fonts/outfit/stylesheet.css',		
		array(),
		wp_get_theme()->get('Version')
	);

    wp_enqueue_style(
		'herson-bootstrap-css',
		get_stylesheet_directory_uri() . '/assets/css/bootstrap/bootstrap.min.css',		
		array(),
		wp_get_theme()->get('Version')
	);

    wp_enqueue_style(
		'herson-aos-css',
		get_stylesheet_directory_uri() . '/assets/aos-master/dist/aos.css',		
		array(),
		wp_get_theme()->get('Version')
	);
	
	// wp_enqueue_style(
	// 	'herson-lightslider-aliados-css',
	// 	get_stylesheet_directory_uri() . '/assets/css/lightslider/lightslider-aliados.css',		
	// 	array(),
	// 	wp_get_theme()->get('Version')
	// ); 
	
	// wp_enqueue_style(
	// 	'herson-lightslider-quickoptions-css',
	// 	get_stylesheet_directory_uri() . '/assets/css/lightslider/lightslider-quickoptions.css',		
	// 	array(),
	// 	wp_get_theme()->get('Version')
	// ); 

	wp_enqueue_style(
		'herson-lightslider-css',
		get_stylesheet_directory_uri() . '/assets/css/lightslider/lightslider.css',		
		array(),
		wp_get_theme()->get('Version')
	); 

}
add_action('wp_enqueue_scripts', 'custom_enqueue_styles_herson');
/* FIN Herson */
