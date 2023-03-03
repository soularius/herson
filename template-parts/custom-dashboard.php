<?php
// Estilos dashboard
function my_admin_styles() {
    echo '<style>
    :root {
        --color-1: #6387f2;
        --color-2: #606060;
        --color-3: #919191;
        --color-4: #e1e1e1;
        --color-5: #ffffff;
        --color-6: #000000;
    }
    li#menu-posts-services .wp-menu-image::before, li#menu-posts-portfolio .wp-menu-image::before, li#menu-posts-resenas .wp-menu-image::before, li#menu-posts-blogs .wp-menu-image::before, li#menu-pages .wp-menu-image::before, li#menu-media .wp-menu-image::before, li#menu-comments .wp-menu-image::before, li#toplevel_page_theme-general-settings .wp-menu-image::before, li#toplevel_page_theme-encuesta-settings .wp-menu-image::before, li#menu-users .wp-menu-image::before , li#menu-dashboard .wp-menu-image::before, #collapse-button .collapse-button-icon:after, li#wp-admin-bar-site-name a.ab-item, .dashicons-products::before, h2.hndle.ui-sortable-handle, .acf-fields > .acf-tab-wrap .acf-tab-group li.active a, li#menu-posts .wp-menu-image:before, li#toplevel_page_ninja-forms .wp-menu-image:before, li#menu-posts-product .wp-menu-image:before, li#toplevel_page_wc-admin-path--analytics-overview .wp-menu-image:before, #toplevel_page_poll-settings .wp-menu-image:before  {
        color: var(--color-1) !important;
    }
    /*#e-admin-top-bar-root, li#menu-posts, li#toplevel_page_wpseo_workouts, .error.notice-error.notice.dce-generic-notice {
         display: none !important;
    }*/
    .acf-field.acf-field-message.acf-field-607e14ac247a7 {
        background: var(--color-1);
    }
    .acf-fields > .acf-tab-wrap .acf-tab-group li a {
        background: var(--color-6);
        color: var(--color-5);
        transition: 0.2s;
    }
    .acf-fields > .acf-tab-wrap .acf-tab-group li a:hover {
        color: var(--color-1);
        transition: 0.2s;
    }
    .wp-core-ui .button-primary {
        border-radius: 0;
        background: var(--color-6);
    }
    .wp-core-ui .button-primary:hover {
        color: var(--color-1);
        background: var(--color-6);
        transition: 0.2s;
    }
    .postbox.acf-postbox {
        background: var(--color-4);
    }
    .acf-fields > .acf-tab-wrap {
        background: var(--color-3);
    }
    .separItem {
        background: var(--color-6);
        opacity: 0.2;
        padding: 1px !important;
    }
    #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {
        background: var(--color-6);
    }
    #adminmenu a:hover {
        color: var(--color-3) !important;
    }
    #toplevel_page_snippets div.wp-menu-image:before, li#toplevel_page_snippets .wp-menu-name {
        color: var(--color-2) !important;
    }
    #fieldEncuesta, #acf-group_6378fdd059986 {
        background: #fff;
    }
</style>';
}

add_action('admin_head', 'my_admin_styles');	
// Fin Estilos Dasboard

// Vista Administradores
if( get_field('habilitar_opciones_de_administrador','options') == 'disable' ): 
    function my_admin_styles2() {
        echo '<style>
        
        li#menu-comments, li#toplevel_page_woocommerce-marketing, li#menu-appearance, li#toplevel_page_yith_plugin_panel, li#menu-plugins, li#toplevel_page_profile-builder, li#menu-tools, li#menu-settings, li#toplevel_page_edit-post_type-acf-field-group, li#toplevel_page_loco, li#toplevel_page_sg-cachepress, li#toplevel_page_sg-security {
            display: none;
        }
        
        li#menu-users li:last-child, li#toplevel_page_snippets ul.wp-submenu.wp-submenu-wrap li.wp-first-item, select#filter-by-form option:nth-child(5), select#filter-by-form option:nth-child(8), select#filter-by-form option:nth-child(10), select#filter-by-form option:nth-child(1), select#filter-by-form option:nth-child(7) {
            display: block;
        }
        li#menu-users a.wp-has-submenu.wp-not-current-submenu.menu-top.menu-icon-users, table.wp-list-table.widefat.fixed.table-view-list.e-form-submissions-list-table.striped.table-view-list td.column-form a {
            pointer-events: none;
        }        
        ul#adminmenu {
            display: flex;
            flex-direction: column;
        }
        li#toplevel_page_snippets {
            order: 20;
        }
    </style>';
    }
    
    add_action('admin_head', 'my_admin_styles2');
endif;
// Fin Vista Administradores

// Menú Opciones Generales
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' 	=> 'Opciones generales',
        'menu_title'	=> 'Opciones generales',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}
// Fin Menú Opciones Generales

// Menú Opciones Generales
if (function_exists('acf_add_options_page')) {

    // acf_add_options_sub_page(array(
    //     'page_title' 	=> 'Encuesta Satisfacción',
    //     'menu_title'	=> 'Encuesta Satisfacción',
    //     'parent_slug' 	=> 'poll-settings',
    // ));

    // Add sub page.
    acf_add_options_sub_page(array(
        'page_title'    => 'Configuración para Envío de email',
        'menu_title'    => 'Configuración para Envío de email',
        'parent_slug' 	=> 'poll-settings',
    ));
}
// Fin Menú Opciones Generales

// Menús Personalizados
function wpb_custom_new_menu()
{
    register_nav_menus(
        array(
            'menu-1-footer' => __('Menu 1 Footer No Logueados'),
            'menu-2-footer' => __('Menu 2 Footer No Logueados'),
            'menu-3-footer' => __('Menu 3 Footer No Logueados'),
            'menu-1-footer-logged' => __('Menu 1 Footer Logueados'),
            'menu-2-footer-logged' => __('Menu 2 Footer Logueados'),
            'menu-3-footer-logged' => __('Menu 3 Footer Logueados'),
            'menu-top-users' => __('Menu Usuarios No Logueados'),
            'menu-top-users-logged' => __('Menu Usuarios Logueados'),
            'menu-top-favoritos-logged' => __('Menu Favoritos Logueados')
        )
    );
}
add_action('init', 'wpb_custom_new_menu');
//   Fin Menú Personalizados

function lista_encuestas()
{
    $menu_slug = 'poll-settings';
    add_menu_page(
        'Estadísticas Encuestas', // page <title>Title</title>
        'Estadísticas Encuestas', // menu link text
        'administrator', // capability to access the page
        $menu_slug, // page URL slug
        'listado_encuesta_data',
        'dashicons-clipboard', // menu icon
        5 // priority
    );
    // add_submenu_page($menu_slug, 'Listado', 'Listado', 'administrator', 'listado_encuesta', 'listado_encuesta_data');
    add_submenu_page(
        $menu_slug,
        'Estadística Generales',
        'Estadística Generales',
        'administrator',
        'poll-average-data',
        'filtrado_encuesta_data'
    );
}
add_action('admin_menu', 'lista_encuestas');


function listado_encuesta_data (){    
    get_template_part('template-parts/encuesta-satisfaccion/listado','encuestas');    
} 

function filtrado_encuesta_data (){    
    get_template_part('template-parts/encuesta-satisfaccion/filtrado','encuestas');    
} 




?>