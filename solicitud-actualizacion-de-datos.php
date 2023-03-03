<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="frmRequest" class="mb-5">
        <div class="container">
            <div class="requestBox jaav">
                <div class="hero col mb-md-5">
                    <div class="imgUpdate mb-4" data-aos="fade-up">
                        <?php
                            $image = get_field('imagen_update');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if ($image) {
                            echo wp_get_attachment_image($image, $size);
                            }
                        ?>
                    </div>
                    <div class="contUpdate" data-aos="fade-up">
                        <div class="title titleHito mb-3">
                            <?php if( get_field('titulo_update') ): 
                                the_field('titulo_update'); 
                            endif; ?>
                        </div> 
                        <div class="textUpdate">
                            <?php if( get_field('extracto_update') ): 
                                the_field('extracto_update'); 
                            endif; ?>
                        </div>                        
                    </div>                    
                </div> 
                <div class="mx-auto">
                    <?php echo do_shortcode(get_field('shortcode_update')); ?>                            
                </div>

            </div>         
        </div>
    </section>
</main>
<?php get_footer();?>