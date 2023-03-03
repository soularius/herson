<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="nosotros" class="mb-5">
        <div class="container">
            <div class="row mb-4"> 
                <div class="hero col mb-md-5">
                    <div class="imgNosotros mb-4" data-aos="fade-up">
                        <?php
                            $image = get_field('imagen_nosotros');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if ($image) {
                            echo wp_get_attachment_image($image, $size);
                            }
                        ?>
                    </div>
                    <div class="contNosotros" data-aos="fade-up">
                        <div class="title">
                            <?php if( get_field('titulo_nosotros') ): 
                                the_field('titulo_nosotros'); 
                            endif; ?>
                        </div>
                        <div class="textNosotros">
                            <?php if( get_field('extracto_nosotros') ): 
                                the_field('extracto_nosotros'); 
                            endif; ?>
                        </div>
                    </div>                    
                </div> 
            </div>
            <div class="row">
                <div class="hitos col">
                <?php

                    // Check rows exists.
                    if( have_rows('hitos') ):

                        // Loop through rows.
                        while( have_rows('hitos') ) : the_row();

                            // Load sub field value.
                            ?>
                            <div class="itemHito row mb-5" data-aos="fade-down">
                                <div class="col-12 col-md-7 col-lg-6 col-a mb-4 mb-md-0">
                                    <div class="titleHito mb-3">
                                        <?php if( get_sub_field('titulo_hito') ): 
                                            the_sub_field('titulo_hito'); 
                                        endif; ?>
                                    </div>
                                    <div class="textHito">
                                        <?php if( get_sub_field('extracto_hito') ): 
                                            the_sub_field('extracto_hito'); 
                                        endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 col-lg-6 col-b">
                                    <?php
                                        $image = get_sub_field('imagen_hitos');
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
                        // Do something...
                    endif;
                ?>

                </div>                              
            </div>            
        </div>
    </section>

    <section id="eventos" class="mb-4">
        <div class="container">
            <div class="row">
                <div class="imbBannerEvento" data-aos="zoom-in-down">
                    <?php
                        $image = get_field('banner_eventos');
                        $size = 'full'; // (thumbnail, medium, large, full or custom size)
                        if ($image) {
                        echo wp_get_attachment_image($image, $size);
                        }
                    ?>
                </div>
                <div class="titleHito text-center my-4">
                    <?php if( get_field('titulo_eventos') ): 
                        the_field('titulo_eventos'); 
                    endif; ?>
                </div>                

                <div class="row align-items-center mb-4 pe-0" data-aos="fade-up-right">
                    <div class="contEvento col-12 col-md-6">
                        <?php if( get_field('contenido_1_eventos') ): 
                            the_field('contenido_1_eventos'); 
                        endif; ?>
                    </div>
                    <div class="imgEvento col-12 col-md-6">
                        <?php
                            $image = get_field('imagen_1_eventos');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if ($image) {
                            echo wp_get_attachment_image($image, $size);
                            }
                        ?>
                    </div>
                </div>

                <div class="row align-items-center mb-4 pe-0" data-aos="fade-up-left">
                    <div class="imgEvento col-12 col-md-6 order-1 order-md-0">
                        <?php
                            $image = get_field('imagen_2_eventos');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if ($image) {
                            echo wp_get_attachment_image($image, $size);
                            }
                        ?>
                    </div>
                    <div class="contEvento col-12 col-md-6 order-0 order-md-1">
                        <?php if( get_field('contenido_2_eventos') ): 
                            the_field('contenido_2_eventos'); 
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer();?>