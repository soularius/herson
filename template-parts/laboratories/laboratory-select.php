<?php

//Devolver datos a archivo js
add_action('wp_ajax_nopriv_laboratories_ajax_select', 'laboratories_ajax_select');
add_action('wp_ajax_laboratories_ajax_select', 'laboratories_ajax_select');

function laboratories_ajax_select()
{
    $id = $_POST['id'];
    $category_name = $_POST['category_name'];
    //Desde la línea 11 a la línea 17 debe convertirse en una function, ya que comienza a ver código duplicado.
    if ($term = get_term_by('id', $id, 'product_cat', 'description')) {
        $thumbnail_id = get_term_meta($id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);
        echo '<img src="' . $image . '" alt="" />';
        echo $term->description;
    }
    //Desde la línea 18 a la línea 21 debe convertirse en una function, ya que comienza a ver código duplicado.
    echo '<div class="mt-5">';
    echo do_shortcode("[products limit='8' category='" . $category_name . "' columns='4' orderby='id' order='DESC' visibility='visible']");
    echo '</div>';
    wp_die();
}
