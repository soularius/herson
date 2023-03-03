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
                                            <?php if( get_sub_field('url_banner_privado') ):?>                                                          
                                                <a href="<?php the_sub_field('url_banner_privado'); ?>">
                                                    <?php if( get_sub_field('boton_banner_privado') ): 
                                                        the_sub_field('boton_banner_privado');                                                         
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
                                        <?php if( get_sub_field('url_banner_privado') ):?>                                                          
                                            <a href="<?php the_sub_field('url_banner_privado'); ?>">
                                                <?php if( get_sub_field('boton_banner_privado') ): 
                                                    the_sub_field('boton_banner_privado');                                                         
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

    <section id="quickOptions" class="my-5 fs--5 text-center">
        <div class="container">
            <div class="row">
                <div class="col">                    
                    <ul id="lightSlider-quickoptions">
                        <li>
                            <a href="#ofertProducts">Ofertas</a>
                        </li>
                        <li>
                            <a href="#newProducts">Nuevos</a>                          
                        </li>   
                        <li>
                            <a href="#discountsProducts">Descuentos</a>                          
                        </li>   
                        <li>
                            <a href="#featuredProducts">Destacados</a>                       
                        </li>   
                        <li>
                            <a href="#bestSellingProducts">Más Vendidos</a>                           
                        </li>                    
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="newProducts" class="productCardList pt-5"  data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_nuevos') ): 
                                the_field('titulo_nuevos'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">
                        <p>
                            <?php if( get_field('titulo_nuevos') ): 
                                the_field('extracto_nuevos'); 
                            endif; ?>
                        </p>
                    </div>
                    <?php 
                        news_category_list();
                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <section id="ofertProducts" class="productCardList bg-3 pt-5" data-aos="fade-right">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_ofertas') ): 
                                the_field('titulo_ofertas'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">                       
                        <p>
                            <?php if( get_field('extracto_ofertas') ): 
                                the_field('extracto_ofertas'); 
                            endif; ?>
                        </p>
                    </div>
                    <?php 
                        oferts_category_list();
                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <section id="discountsProducts" class="productCardList pt-5"  data-aos="fade-left">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_descuentos') ): 
                                the_field('titulo_descuentos'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">
                        <p>
                            <?php if( get_field('extracto_descuentos') ): 
                                the_field('extracto_descuentos'); 
                            endif; ?>
                        </p>
                    </div>
                    <?php 
                        discounts_category_list();
                    ?>        
                </div>
            </div>
        </div>
    </section>
    
    <section id="featuredProducts" class="productCardList bg-3 pt-5" data-aos="fade-right">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_destacados') ): 
                                the_field('titulo_destacados'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">
                        <p>
                            <?php if( get_field('extracto_destacados') ): 
                                the_field('extracto_destacados'); 
                            endif; ?>
                        </p>
                    </div>
                    <?php 
                        featured_category_list();
                    ?>                
                </div>
            </div>
        </div>
    </section>
    
    <section id="bestSellingProducts" class="productCardList pt-5"  data-aos="fade-left">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="titleProductsSections text-center">
                        <h2 class="color-1 fw-600">
                            <?php if( get_field('titulo_mas_vendidos') ): 
                                the_field('titulo_mas_vendidos'); 
                            endif; ?>
                        </h2>
                    </div>
                    <div class="textProductsSections color-3 text-center mb-5">
                        <p>
                            <?php if( get_field('extracto_mas_vendidos') ): 
                                the_field('extracto_mas_vendidos'); 
                            endif; ?>
                        </p>
                    </div>
                    <?php 
                        best_selling_category_list();
                    ?>         
                </div>
            </div>
        </div>
    </section>   
    
    <section id="ourAllies" class="bg-3 py-5" data-aos="fade-right">
        <div class="container">
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

                                            // Load sub field value.
                                            ?>

                                            <li class="item">
                                                <a href="<?php if( get_sub_field('url_marca') ): the_sub_field('url_marca'); endif; ?>" target="_blank"><?php
                                                    $image = get_sub_field('imagen_aliado');
                                                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                                                    if ($image) {
                                                        echo wp_get_attachment_image($image, $size);
                                                    }
                                                    ?>
                                                </a>
                                            </li>
                                        <?php
                                // End loop.
                                endwhile;
                            endif;
                            ?>
                        </ul>
                            
                        <!-- <div class="owl-container">
                            <div class="owl-carousel owl-theme">
                            <?php
                                // Check rows exists.
                                if( have_rows('marca') ):

                                    // Loop through rows.
                                    while( have_rows('marca') ) : the_row();

                                        // Load sub field value.
                                        ?>
                                        <div class="item">
                                            <a href="<?php if( get_sub_field('url_marca') ): the_sub_field('url_marca'); endif; ?>" target="_blank"><?php
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
                            </div>                           
                        </div> -->
                    </div>

                </div>
            </div>
        </div>

    </section>
</main>