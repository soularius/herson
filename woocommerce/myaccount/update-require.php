<?php

?>
 <main>
    <section id="frmRequest" class="mb-5">
        <div class="container">
            <div class="requestBox jaavv">
                <div class="hero col mb-md-4">
                    <div class="imgUpdate" data-aos="fade-up">
                        <?php
                            $image = get_field('imagen_update','option');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if ($image) {
                            echo wp_get_attachment_image($image, $size);
                            }
                        ?>
                    </div>
                    <div class="contUpdate" data-aos="fade-up">
                        <div class="title titleHito mb-3">
                            <?php if( get_field('titulo_update','option') ): 
                                the_field('titulo_update','option'); 
                            endif; ?>
                        </div>
                        <div class="textUpdate">
                            <?php if( get_field('extracto_update','option') ): 
                                the_field('extracto_update','option'); 
                            endif; ?>
                        </div>                                                                      
                    </div>                    
                </div>  

                <div class="accordion accordion-flush mb-5" id="accordionFlushInstructions">
                <?php

                // Check rows existexists.
                if( have_rows('instrucciones','option') ):
                    $i = 1;
                    // Loop through rows.
                    while( have_rows('instrucciones','option') ) : the_row();                        
                        // Do something...
                        ?>
                            <div class="accordion-item">
                                <span class="accordion-header" id="flush-heading-<?php echo $i; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-<?php echo $i; ?>" aria-expanded="false" aria-controls="flush-collapse-<?php echo $i; ?>">
                                    <?php if( get_sub_field('titulo_instruccion','option') ): 
                                        the_sub_field('titulo_instruccion','option'); 
                                    endif; ?>
                                </button>
                                </span>
                                <div id="flush-collapse-<?php echo $i; ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading-<?php echo $i; ?>" data-bs-parent="#accordionFlusInstructions">
                                    <div class="accordion-body">
                                        <?php if( get_sub_field('extracto_instruccion','option') ): 
                                            the_sub_field('extracto_instruccion','option'); 
                                        endif; ?>
                                    </div>
                                </div>
                            </div>                    
                        <?php
                        $i++;
                    // End loop.
                    endwhile;
                endif; ?>


                </div>

                <div class="mx-auto">
                    <?php echo do_shortcode(get_field('shortcode_update','option')); ?>                            
                </div>
                <div>
                <?php global $current_user; wp_get_current_user(); ?>
                    <!-- 
                        <?php 
                            if ( is_user_logged_in() ) { 
                                echo 'Username: ' . $current_user->user_login . "\n"; echo 'User display name: ' . $current_user->display_name . "\n"; 
                            } 
                            else {
                                wp_loginout();
                            } 
                    //var_dump($current_user);
                    ?> -->

                    <?php
                        $user_id = get_current_user_id();
                        // $key = 'blocking_users';
                        // $single = false;
                        // $blocking_users = get_user_meta( $user_id, $key, $single ); 
                        // echo '<p>The '. $key . ' value for user id ' . $user_id . ' is: </p>'; 
                        
                        $meta = get_user_meta( $user_id );
                        $prueba = $meta->id_cliente;
                        $identif =  "";
                        if(get_field("identif", "user_$user_id")) {
                            $identif = get_field("identif", "user_$user_id");
                        }


                    ?>
                </div>

            </div>         
        </div>
    </section>
</main>
<script>
    document.getElementById("myAccount").classList.add ("updateRequire");
</script>