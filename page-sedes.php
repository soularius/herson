<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="sedes" class="mb-5">
        <div class="container">
            <div class="row hero mb-5"> 
                <div class="col-12 col-md-6 col-a mb-4 mb-md-0" data-aos="fade-up-right">
                    <div class="titleSedesGral mb-3">
                        <?php if( get_field('titulo_sedes') ): 
                            the_field('titulo_sedes'); 
                        endif; ?>
                    </div>
                    <div class="contentSedesGral">
                        <?php if( get_field('contenido_sedes') ): 
                            the_field('contenido_sedes'); 
                        endif; ?>
                    </div>
                </div>
                <div class="imgNuestraSedes col-12 col-md-6 col-b d-flex flex-column justify-content-center" data-aos="fade-up-left">                    
                    <?php
                        $image = get_field('imagen_sedes');
                        $size = 'full'; // (thumbnail, medium, large, full or custom size)
                        if ($image) {
                        echo wp_get_attachment_image($image, $size);
                        }
                    ?>
                </div>               
            </div>
            <div class="sedesList">
                <?php

                // Check rows exists.
                if( have_rows('sedes') ):

                    // Loop through rows.
                    while( have_rows('sedes') ) : the_row();
                        // Load sub field value.
                        ?>
                        <div class="row itemSede mb-5" data-aos="zoom-in">
                            <div class="col-12 col-md-6 col-a mb-4 mb-md-0 d-flex flex-column justify-content-center">
                                <div class="titleSede mb-2">
                                    <?php if( get_sub_field('titulo_sede') ): 
                                        the_sub_field('titulo_sede'); 
                                    endif; ?>
                                </div>
                                <div class="contentSede mb-4">
                                    <?php if( get_sub_field('contenido_sede') ): 
                                        the_sub_field('contenido_sede'); 
                                    endif; ?>
                                </div>
                                <div class="imgSede d-md-none mb-4">
                                    <?php
                                        $image = get_sub_field('imagen_sede');
                                        $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                        if ($image) {
                                        echo wp_get_attachment_image($image, $size);
                                        }
                                    ?>
                                </div>
                                <div class="phoneSede">
                                    <?php if( get_sub_field('telefono_sede') ): 
                                        the_sub_field('telefono_sede'); 
                                    endif; ?>
                                </div>
                                <div class="addressSede">
                                    <?php if( get_sub_field('direccion_sede') ): 
                                        the_sub_field('direccion_sede'); 
                                    endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-b d-none d-md-block">
                                <?php
                                    $image = get_sub_field('imagen_sede');
                                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                    if ($image) {
                                    echo wp_get_attachment_image($image, $size);
                                    }
                                ?>
                            </div>

                        </div>
                        <?php                       
                    // End loop.
                    endwhile;

                // No value.
                else :
                    
                endif;
                ?>
                
            </div>            
        </div>
    </section>
</main>
<?php get_footer();?>