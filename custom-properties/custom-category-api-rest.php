<?php

add_action('rest_api_init', function () {
    register_rest_route('erp/v1', '/category/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_product_category',
    ));
});

function get_product_category(WP_REST_Request $request)
{

    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
    }

    $category_id = $request->get_param('id');
    $category = get_term_by('id', $category_id, 'product_cat');
    $subcategories = get_term_children($category_id, 'product_cat');
    $subcategories_with_id_item = array();

    foreach ($subcategories as $subcat) {
        $subcat_obj = get_term($subcat, 'product_cat');
        $subcat_id_item = get_field('id_item', $subcat_obj);
        $subcat_arr = array(
            'term_id' => $subcat_obj->term_id,
            'name' => $subcat_obj->name,
            'slug' => $subcat_obj->slug,
            'id_item' => $subcat_id_item
        );
        array_push($subcategories_with_id_item, $subcat_arr);
    }

    $category_id_item = get_field('id_item', $category);
    $category_arr = array(
        'term_id' => $category->term_id,
        'name' => $category->name,
        'slug' => $category->slug,
        'id_item' => $category_id_item,
        'subcategories' => $subcategories_with_id_item
    );

    $respuesta = array(
        'category' => $category_arr,
    );

    return rest_ensure_response($respuesta);
}
