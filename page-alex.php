<?php get_header(); ?>

<?php

// Listado de productos para obtener la cantidad del amarre
$all_data = get_field("producto_en_sede", '64478');
$listado_de_productos = array_column($all_data, "listado_de_productos");

if (have_rows('producto_en_sede', '64478')) :
    while (have_rows('producto_en_sede', '64478')) : the_row();
        if (get_row_layout() == 'amarre_sede') :
            if (have_rows('listado_de_productos')) :
                while (have_rows('listado_de_productos')) : the_row();
                    echo get_sub_field('cantidad');
                endwhile;
            endif;
        endif;
    endwhile;
endif;

?>

<?php get_footer(); ?>