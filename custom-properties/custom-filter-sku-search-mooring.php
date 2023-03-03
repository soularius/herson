<?php

add_filter('acf/fields/post_object/query/name=producto', 'products_am_object_query', 10, 3);

function products_am_object_query($args, $field, $post_id) {
	$filter = array_search('product', $args['post_type']);
    if ($field['name'] == 'producto' && $field['ID'] == 1252 && $filter !== false) {
        $args['meta_query'] = array(
			'relation' => 'OR',
            array(
                'key' => '_sku',
                'value' => $args['s'],
                'compare' => '='
            )
        );
	    add_filter('posts_where', 'cf_search_where');
    }
    return $args;
}

function cf_search_where($where) {
    global $wpdb;
    $pos = strpos($where, "AND", strpos($where, "AND")+1);
    $where = substr_replace($where, "OR", $pos, 3);

    return $where;
}