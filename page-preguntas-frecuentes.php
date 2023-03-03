<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="preguntasFrecuentes" class="mb-5">
        <div class="container">
            <div class="row">                
                <div class="col">
                    <h1 class="fs--3 color-1 fw-900 mb-5">Preguntas frecuentes</h1>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                    
                    <?php

                    // Check rows exists.
                    if( have_rows('preguntas_frecuentes') ):
                        $i=1;
                        // Loop through rows.
                        while( have_rows('preguntas_frecuentes') ) : the_row();
                        ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading<?php echo $i; ?>">
                                <button class="accordion-button collapsed fw-400" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $i; ?>">
                                    <?php if( get_sub_field('pregunta') ): 
                                        the_sub_field('pregunta'); 
                                    endif; ?>
                                </button>
                                </h2>
                                <div id="flush-collapse<?php echo $i; ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $i; ?>" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <?php if( get_sub_field('respuesta') ): 
                                        the_sub_field('respuesta'); 
                                    endif; ?>
                                </div>
                                </div>
                            </div>
                        <?php  
                        // End loop.
                        $i++;
                        endwhile;                    
                    endif;
                    ?>
                    </div>
                </div>
            </div>            
        </div>
    </section>
</main>
<?php get_footer();?>