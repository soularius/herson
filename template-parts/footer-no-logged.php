<?php

?>
<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="boxFooter bg-1 py-4 py-md-5 px-4">
			<div class="container">
				<div class="row">
					<div class="col-12 col-lg-8 col-xl-9 d-flex flex-column justify-content-evenly px-0">
						<div class="row menuFooterCols">
							<div class="col-12 col-md-4">
								<?php
									wp_nav_menu( array( 
										'theme_location' => 'menu-1-footer', 
										'container_class' => 'custom-footer-menu' ) ); 
								?>
							</div>
							<div class="col-12 col-md-4">
								<?php
									wp_nav_menu( array( 
										'theme_location' => 'menu-2-footer', 
										'container_class' => 'custom-footer-menu' ) ); 
								?>
							</div>
							<div class="col-12 col-md-4">
								<?php
									wp_nav_menu( array( 
										'theme_location' => 'menu-3-footer', 
										'container_class' => 'custom-footer-menu' ) ); 
									?>
							</div>
						</div>
						<div class="row pt-5 d-none d-lg-block">
							<div class="col">
                                <div class="extractFter mb-4 pe-4">
                                    <?php if( the_field('extracto_footer','option') ): 
                                        the_field('extracto_footer', 'option');
                                    endif; ?>
                                </div>
                                <div class="socialSites d-flex">
                                        <?php

                                        // Check rows exists.
                                        if( have_rows('redes_sociales','option') ):

                                            // Loop through rows.
                                            while( have_rows('redes_sociales','option') ) : the_row();

                                                // Load sub field value.
                                                if( get_sub_field('icono_red_social','option') ): 
                                                    $image = get_sub_field('icono_red_social','option');
                                                    ?><a href="<?php if( get_sub_field('url_red_social','option') ):the_sub_field('url_red_social','option');endif; ?>
                                                    " target="_blank"><img src="<?php echo $image; ?>" alt=""></a><?php
                                                endif;
                                                // Do something...

                                            // End loop.
                                            endwhile;

                                        endif;?>
                                </div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4 col-xl-3">
						<div class="row">
							<div class="col p-0">
                                <?php 
                                if( get_field('url_titulo_sedes','option') ): ?>
                                    <a href="<?php the_field('url_titulo_sedes','option');  ?>">
                                <?php endif;
                                ?>                                
                                    <div class="sedesTitle fw-700 fs--8 mb-3 mt-md-4">
                                        <?php if( the_field('titulo_sedes','option') ): 
                                            the_field('titulo_sedes', 'option');
                                        endif; ?>
                                    </div>
                                <?php 
                                if( get_field('url_titulo_sedes','option') ): ?>
                                    </a>
                                <?php endif;
                                ?>                                
								<div class="sedesList fs--9">
                                    <?php                                   

                                    // Check rows exists.
                                    if( have_rows('sedes','option') ):
                                    
                                        // Loop through rows.
                                        while( have_rows('sedes','option') ) : the_row();
                                    
                                            // Load sub field value.                                            
                                            ?>
                                            <p class="fw-600 mb-0"><?php
                                            if( the_sub_field('sede','option') ): 
                                                the_sub_field('sede', 'option');
                                            endif;
                                            ?></p>
                                            <p>
                                            <?php
                                            if( the_sub_field('datos_sede','option') ): 
                                                the_sub_field('datos_sede', 'option');
                                            endif;
                                            ?>
                                            </p>
                                            <?php
                                        // End loop.
                                        endwhile;
                                                                        
                                    endif;
                                    ?>
                                </div>
                                <div class="socialSites d-flex d-lg-none justify-content-center pt-2">
                                        <?php

                                        // Check rows exists.
                                        if( have_rows('redes_sociales','option') ):

                                            // Loop through rows.
                                            while( have_rows('redes_sociales','option') ) : the_row();

                                                // Load sub field value.
                                                if( get_sub_field('icono_red_social','option') ): 
                                                    $image = get_sub_field('icono_red_social','option');
                                                    ?><a href="<?php if( get_sub_field('url_red_social','option') ):the_sub_field('url_red_social','option');endif; ?>
                                                    " target="_blank"><img src="<?php echo $image; ?>" alt=""></a><?php
                                                endif;
                                                // Do something...

                                            // End loop.
                                            endwhile;

                                        endif;?>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="end-footer bg-2 p-3 text-center d-flex flex-column d-lg-block">            
            <p class="txtFooterBtn d-flex flex-column flex-lg-row justify-content-center mb-0"><span class="contRights me-lg-2"> Todos los derechos reservados. <?php echo date("Y"); ?></span>
            <span class="contBrandDev d-flex justify-content-center">Diseñado y Desarrollado por 
            <a href="https://furore.co/" target="_blank">
                <img src="../wp-content/uploads/2022/08/logo-furore.png" alt="" srcset="">
            </a>
            </span>	</p>			
			
		</div>
	</footer><!-- #colophon -->