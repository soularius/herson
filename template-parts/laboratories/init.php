<?php

//Get template for laboratories template - AJAX
get_template_part( 'template-parts/laboratories/laboratory-select');

function custom_script_laboratories()
{
    if(!is_page('laboratorios')) return;
    wp_enqueue_script( 'laboratories-script-js', get_stylesheet_directory_uri() . '/template-parts/laboratories/js/script.js', array( 'jquery' ),'',true );			
    wp_localize_script('laboratories-script-js','laboratories_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'custom_script_laboratories'); 

?>