<main>
    <section id="sliderHero">
        <div class="col slideJumb">
            <div id="carouselHerson" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <?php if( have_rows('banners') ): ?>
        
                        <div class="carousel-inner">

                        <?php $estado = "active"; ?>
                        <?php while( have_rows('banners') ): the_row();
                            $image = get_sub_field('imagen_banner');
                            $imageMoviles = get_sub_field('imagen_moviles_banner');
                            $imageBrand = get_sub_field('marca_banner');
                            
                            $cont++;
                            $class = "SlderItem" . $cont;
                        ?>
                
                        <div id="<?php echo $class ?>" class="carousel-item <?php echo $estado ?>">

                            <?php if( get_sub_field('url_banner') && get_sub_field('cta_banner')!='hide' ):?>
                                <a href="<?php the_sub_field('url_banner'); ?>">                                        
                            <?php endif; ?>

                            <div class="imgBannBox">                                        
                                <img src="<?php echo $image; ?>" id="imgBann" class="d-none d-md-block" alt="">
                                <img src="<?php echo $imageMoviles; ?>" id="imgBannMov" class="d-md-none" alt="">                                                                            
                            </div>

                            <?php if( get_sub_field('url_banner') ):?>
                                </a>
                            <?php endif; ?>
    
                            <div class="contentBox position-absolute d-flex flex-column">
                                <div class="imgBrand">
                                    <img src="<?php echo $imageBrand; ?>" alt="">
                                    <?php if( get_sub_field('cta_banner') && get_sub_field('cta_banner')!='hide' ):?>
                                        <div class="btnBannerMov siteBtn">
                                            <?php if( get_sub_field('url_banner') ):?>                                                          
                                                <a href="<?php the_sub_field('url_banner'); ?>">
                                                    <?php if( get_sub_field('boton_banner') ): 
                                                        the_sub_field('boton_banner');                                                         
                                                    endif; ?>                            
                                                </a>
                                            <?php endif; ?>
                                        </div>                                
                                    <?php endif; ?>                                   
                                </div>
                                                                                                        
                                <div class="extractBox fs--6 my-3 px-4 px-lg-0" >
                                    <?php if( get_sub_field('extracto_banner') ): 
                                        the_sub_field('extracto_banner'); 
                                    endif; ?>
                                </div>
                                
                                <?php if( get_sub_field('cta_banner') && get_sub_field('cta_banner')!='hide' ):?>
                                    <div class="btnBanner siteBtn">
                                        <?php if( get_sub_field('url_banner') ):?>                                                          
                                            <a href="<?php the_sub_field('url_banner'); ?>">
                                                <?php if( get_sub_field('boton_banner') ): 
                                                    the_sub_field('boton_banner');                                                         
                                                endif; ?>                            
                                            </a>
                                        <?php endif; ?>
                                    </div>                                
                                <?php endif; ?>                                   

                            </div>
                                                    
                        </div>
                        <?php $estado = ""; ?>
                        <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
    
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHerson" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="false"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselHerson" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="false"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
    
            </div>
        </div>
    </section> 

    <section id="quiene-somos">
        <div class="container py-5 px-4 px-lg-0">
            <div class="row">
                <div class="col-12 col-md-6 col-a mt-5 mt-md-0" data-aos="fade-right">
                    <div class="titleQuienes fs--3 fw-900 color-1 lh-1">
                        <?php if( get_field('titulo_quienes') ): 
                            the_field('titulo_quienes'); 
                        endif; ?>
                    </div>
                    <div class="contentQuienes color-3 my-4 fs--6">
                        <?php if( get_field('extracto_quienes') ): 
                            the_field('extracto_quienes'); 
                        endif; ?>
                    </div>
                    <div class="btnQuienes siteBtn text-center text-md-start">
                        <?php 
                        $link = get_field('boton_quienes');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-b" data-aos="fade-left">
                    <?php
                        $image = get_field('imagen_quienes');
                        $size = 'full'; // (thumbnail, medium, large, full or custom size)
                        if ($image) {
                        echo wp_get_attachment_image($image, $size);
                        }
                    ?>                        
                </div>
            </div>
        </div>
    </section>

    <section id="aliados" class="homeProductsList bg-3 py-5">
        <div class="container px-4 px-lg-0" data-aos="fade-right">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_nuestros_aliados') ): 
                                the_field('titulo_nuestros_aliados'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">
                        <p>
                            <?php if( get_field('extracto_nuestros_aliados') ): 
                                the_field('extracto_nuestros_aliados'); 
                            endif; ?>
                        </p>
                    </div>
                    <div class="carouselAllies">
                        <ul id="lightSlider-aliados">
                            <?php

                            // Check rows exists.
                            if( have_rows('marca') ):

                                // Loop through rows.
                                while( have_rows('marca') ) : the_row();
                                ?>
                                <div class="imgAliados">
                                    <a href="<?php if( get_sub_field('url_marca') ): the_sub_field('url_marca'); endif; ?>" target="_blank">                                    
                                        <?php
                                            $image = get_sub_field('imagen_aliado');
                                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                            if ($image) {
                                            echo wp_get_attachment_image($image, $size);
                                            }
                                        ?>
                                    </a>
                                </div>
                                <?php
                                // End loop.
                                endwhile;

                            endif; 
                            ?>                                             
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>    

    <section id="fortalezas">
        <div class="container py-5 px-4 px-lg-0">
            <?php

            // Check rows exists.
            if( have_rows('fortalezas') ):

                // Loop through rows.
                while( have_rows('fortalezas') ) : the_row();
                    // Load sub field value.
                    ?>
                    <div class="row itemSkils mb-4">
                        <div class="col-12 col-md-6 col-a">
                            <?php
                                $image = get_sub_field('imagen_fortaleza');
                                $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                if ($image) {
                                echo wp_get_attachment_image($image, $size);
                                }
                            ?>
                        </div>
                        <div class="col-12 col-md-6 col-b">
                            <div class="titleFortaleza fs--3 fw-900 color-1 lh-1">
                                <?php if( get_sub_field('titulo_fortaleza') ): 
                                    the_sub_field('titulo_fortaleza'); 
                                endif; ?>
                            </div>
                            <div class="extractoFortaleza color-3 my-4">
                                <?php if( get_sub_field('extracto_fortaleza') ): 
                                    the_sub_field('extracto_fortaleza'); 
                                endif; ?>
                            </div>
                            <div class="btnFortaleza linkSite">
                                <?php 
                                $link = get_sub_field('boton_fortaleza');
                                if( $link ): 
                                    $link_url = $link['url'];
                                    $link_title = $link['title'];
                                    $link_target = $link['target'] ? $link['target'] : '_self';
                                    ?>
                                    <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                                <?php endif; ?>
                            </div>
                            
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
    </section>

    <section id="sedes">  
        <div class="container px-4 px-lg-0">
            <div class="row hero mb-5"> 
                <div class="col-12 col-md-6 col-a mb-4 mb-md-">
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
                    <div class="btnSedes linkSite">
                        <?php 
                        $link = get_field('boton_sedes');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-b">                    
                    <?php
                        $image = get_field('imagen_sedes');
                        $size = 'full'; // (thumbnail, medium, large, full or custom size)
                        if ($image) {
                        echo wp_get_attachment_image($image, $size);
                        }
                    ?>
                </div>               
            </div>          
        </div>
    </section>

    <section id="newArticles">            
        <div class="container boxSideBar my-5 p-0">	
                
            <div class="row px-5 py-4">
                <h2 class="fs--5 color-1 fw-600 px-3 mb-3 text-center">Entérate de nuestros nuevos artículos</h2>
                <?php		
                // Define our WP Query Parameters
                
                $the_query = new WP_Query( 'cat=45','posts_per_page=4' );                     
                $j = 1;
                
                // Start our WP Query
                while ($the_query -> have_posts()) : $the_query -> the_post();
                // Display the Post Title with Hyperlink
                ?>
                <div class="item number-<?php echo $j; ?> col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

                    <div class="imgThumbPost col">
                        <?php the_post_thumbnail( 'thumbnail' );  ?>
                    </div>
                    <div class="col contentCard px-2 mt-3">
                        <div class="col ttlPost">
                            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        </div>
    
                        <div class="col contPost">
                            <?php
                                // Display the Post Excerpt
                                // the_excerpt(__('(more…)'));
                                $excerpt = get_the_excerpt(); 			
                                $excerpt = substr( $excerpt, 0, 45 ); // Only display first 260 characters of excerpt
                                $result = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
                                echo $result;
                                
                            ?>
                        </div>
    
                        <div class="col linkPost">
                            <a href="<?php the_permalink() ?>">Ver más...</a>
                        </div>			
                    </div>

                </div>
                <?php
                $j++;
                // Repeat the process and reset once it hits the limit
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <div class="formSuscribete p-3">
                <div class="container">
                    <div class="row">
                        <div class="col frmSuscribeHome">
                            <span class="labelForm fs--4 fw-600">Suscríbete</span>
                            <?php echo do_shortcode("[ninja_form id=3]");?>                
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </section>

    <section id="testimonios">
        <div class="container">
            <div class="row">
                <h2 class="fs--5 color-1 fw-600 px-3 text-center">Lo que dicen nuestros clientes</h2>
                <div class="testimoniosBox my-5">
                <ul id="lightSlider-testimonios">

                    <?php
                    // Check rows exists.
                    if( have_rows('testimonios') ):

                        // Loop through rows.
                        while( have_rows('testimonios') ) : the_row();
                            // Load sub field value.
                            ?>
                            <li>
                                <div class="row">
                                    <div class="col-a col-12 col-md-5">
                                    <div class="imgTestimonio">
                                        <?php
                                            $image = get_sub_field('imagen_testimonio');
                                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                            if ($image) {
                                            echo wp_get_attachment_image($image, $size);
                                            }
                                        ?>
                                    </div>

                                    </div>
                                    <div class="col-b col-12 col-md-7">
                                        <div class="nombreTestimonio fs--5 color-5">
                                            <?php if( get_sub_field('nombre_testimonio') ): 
                                                the_sub_field('nombre_testimonio'); 
                                            endif; ?>                                            
                                        </div>
                                        <div class="tituloTestimonio color-5 fw-500">
                                            <?php if( get_sub_field('titulo_testimonio') ): 
                                                the_sub_field('titulo_testimonio'); 
                                            endif; ?>                                            
                                        </div>
                                        <div class="comentarioTestimonio color-7">
                                            <?php if( get_sub_field('comentario_testimonio') ): 
                                                the_sub_field('comentario_testimonio'); 
                                            endif; ?>                                            
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php

                        // End loop.
                        endwhile;
                    
                    endif;

                    ?>                        
                    </ul>
                </div>
            </div>
        </div>                    
    </section>    
</main>