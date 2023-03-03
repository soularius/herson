<?php
/*
 * Part 1. Add Link (Tab) to My Account menu
 */
add_filter ( 'woocommerce_account_menu_items', 'update_require_link', 40 );
function update_require_link( $menu_links ){
 
	$menu_links = array_slice( $menu_links, 0, 4, true ) 
	+ array( 'peticion-actualizacion' => 'Petición de actualización' )
	+ array_slice( $menu_links, 4, NULL, true );
 
	return $menu_links;
}
/*
 * Part 2. Register Permalink Endpoint
 */
add_action( 'init', 'update_require_add_endpoint' );
function update_require_add_endpoint() {
 
	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'peticion-actualizacion', EP_PAGES );
 
}
/*
 * Part 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_peticion-actualizacion_endpoint', 'update_require_my_account_endpoint_content' );
function update_require_my_account_endpoint_content() {
 
	// Of course, you can print dynamic content here, one of the most useful functions here is get_current_user_id()	
    get_template_part('woocommerce/myaccount/update','require');   
 
}