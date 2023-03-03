<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="frmContacto" class="mt-4 mb-5">
        <div class="container">
            <div class="row">                
                <div class="col-12 col-lg-7">

                    <div id="horarios">
                        <h1 class="fs--3 fw-900 color-1"><?php echo get_the_title( get_option('page_for_posts', true) ); ?></h1>
                        <div class="listHorario">
                            <?php

                            // Check rows exists.
                            if( have_rows('horarios_de_atencion') ):

                                // Loop through rows.
                                while( have_rows('horarios_de_atencion') ) : the_row();

                                    ?>
                                        <div class="timeHorario color-3">                                    
                                            <?php
                                                if( get_sub_field('horario') ): 
                                                    the_sub_field('horario'); 
                                                endif;
                                            ?>
                                        </div>
                                        <div class="infoHorario mt-2 color-3">                                        
                                            <?php
                                                if( get_field('observaciones') ): 
                                                    the_field('observaciones'); 
                                                endif;
                                            ?>
                                        </div>                                     
                                    <?php

                                // End loop.
                                endwhile;

                            // No value.
                            else :
                                // Do something...
                            endif; ?>
                        </div>
                    </div>

                    <div id="canal">
                        <div class="titleCanal mt-4 mb-3 color-5">
                            <?php if( get_field('titulo_canal') ): 
                                the_field('titulo_canal'); 
                            endif; ?>
                        </div>

                        <div class="listCanales">
                            <?php

                                // Check rows exists.
                                if( have_rows('canales') ):
                                    ?>
                                    <div class="row">
                                    <?php

                                    // Loop through rows.
                                    while( have_rows('canales') ) : the_row();

                                        // Load sub field value.
                                        ?>                                         
                                            <div class="lineCanal col-12 col-lg-6 mb-3">                                            
                                            <img class="me-2" src="<?php if( get_sub_field('icono') ): the_sub_field('icono'); endif; ?>" alt="">
                                        <?php                            
                                        if( get_sub_field('item_canal') ): 
                                            the_sub_field('item_canal'); 
                                        endif;
                                        ?>
                                            </div>                                            
                                        <?php
                                        
                                    // End loop.
                                    endwhile;
                                    ?>
                                    </div>
                                    <?php

                                // No value.
                                else :
                                    // Do something...
                                endif; 
                            ?>
                        </div>

                    </div>

                    <div id="oficinas">
                        <div class="contentOffice color-3">
                            <?php if( get_field('contenido_oficina') ): 
                                the_field('contenido_oficina'); 
                            endif; ?>
                        </div>
                        <p class="lblUbicación mt-3">Ubicación</p>

                        <!-- <div class="listCanales">
                            <form>                            
                            <select name="office" id="officeList">
                                <?php
                                $i=1;
                                // Check rows exists.
                                if( have_rows('titulo_oficinas') ):

                                    // Loop through rows.
                                    while( have_rows('titulo_oficinas') ) : the_row();

                                        ?>
                                            <option value="office-<?php echo $i; ?>"><?php if( get_sub_field('nombre_oficina') ): the_sub_field('nombre_oficina'); endif; ?></option>
                                        <?php
                                        $i++;

                                    // End loop.
                                    endwhile;
                                endif;
                                ?>                               
                            </select>                            
                            </form>
                            <?php
                            $j=1;
                            // Check rows exists.
                            if( have_rows('titulo_oficinas') ):
                            
                                // Loop through rows.
                                while( have_rows('titulo_oficinas') ) : the_row();
                                    ?>
                                        <div id="office-<?php echo $j; ?>" class="dataItem d-none">
                                            <?php
                                            // Check rows exists.
                                            if( have_rows('items_oficinas') ):

                                                // Loop through rows.
                                                while( have_rows('items_oficinas') ) : the_row();
                                                ?>                                         
                                                    <div class="lineCanal mb-3">                                            
                                                    <img class="me-2" src="<?php if( get_sub_field('icono_item') ): the_sub_field('icono_item'); endif; ?>" alt="">
                                                <?php                            
                                                if( get_sub_field('canal_item') ): 
                                                    the_sub_field('canal_item'); 
                                                endif;
                                                ?>
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
                                    <?php    
                                    $j++;                        
                                // End loop.
                                endwhile;
                            
                            // No value.
                            else :
                                // Do something...
                            endif;
                            ?>
                        </div> -->

                        <div id="oficinas" class="listCanales">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php
                                $state = "active";
                                $i = 1;
                                // Check rows exists.
                                if( have_rows('titulo_oficinas') ):

                                    // Loop through rows.
                                    while( have_rows('titulo_oficinas') ) : the_row();
                                    ?>
                                        <button class="nav-link mb-3 <?php echo $state ?>" id="v-pills-<?php echo $i ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo $i ?>" type="button" role="tab" aria-controls="v-pills-<?php echo $i ?>" aria-selected="true"><?php if( get_sub_field('nombre_oficina') ): the_sub_field('nombre_oficina'); endif; ?></button>
                                    <?php
                                    // End loop.
                                    $state = "";
                                    $i++;
                                    endwhile;
                                endif;
                                ?>                                
                                </div>
                                <div class="tab-content" id="v-pills-tabContent">
                                    <?php
                                    $stateCont = "active";
                                    $j = 1;
                                    // Check rows exists.
                                    if( have_rows('titulo_oficinas') ):

                                        // Loop through rows.
                                        while( have_rows('titulo_oficinas') ) : the_row();
                                        ?>
                                            <div class="tab-pane fade show <?php echo $stateCont ?>" id="v-pills-<?php echo $j ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $j ?>-tab">
                                            <?php
                                            // Check rows exists.
                                            if( have_rows('items_oficinas') ):

                                                // Loop through rows.
                                                while( have_rows('items_oficinas') ) : the_row();
                                                ?>                                         
                                                    <div class="lineCanal mb-3">                                            
                                                    <img class="me-2" src="<?php if( get_sub_field('icono_item') ): the_sub_field('icono_item'); endif; ?>" alt="">
                                                <?php                            
                                                if( get_sub_field('canal_item') ): 
                                                    the_sub_field('canal_item'); 
                                                endif;
                                                ?>
                                                    </div>                                            
                                                <?php  
                                                // End loop.
                                                endwhile;
                                            endif;
                                            $stateCont = "";
                                            $j++;
                                            ?>
                                            </div>
                                            <?php                                        
                                        // End loop.
                                        
                                        endwhile;
                                    endif;
                                    ?>                                    
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-12 col-lg-5">
                    <div class="boxContactoForm">
                        <?php echo do_shortcode(get_field('shortcode_formulario')); ?>
                    </div>
                </div>
            </div>            
        </div>
    </section>
</main>
<script>

</script>
<?php get_footer();?>