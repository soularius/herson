<?php get_header(); ?>
<div id="primary" class="">
    <main id="main" class="site-main" role="main">
        <section id="laboratorios" class="productCardList mb-5">
            <div class="container">
                <div class="row titleLab">
                    <div class="col-12 color-1">
                        <h1 class="fs--3 fw-900 color-1"><?php the_title(); ?></h1>
                    </div>
                    <div id="menuLab" class="col-12 my-3">
                        <div class="alphaMenu text-end" data-aos="fade-right">
                            <?php
                            $Letters = range('A', 'Z');
                            $i = 1;
                            $let = 'A';
                            
                            $query = array(
                                'taxonomy'   => 'product_cat', // <-- Custom Taxonomy name..
                                'orderby'    => 'name',
                                'order'      => 'ASC',
                                'hide_empty' => true,
                                'child_of'   => '46',
                            );
                            $taxonomies = get_terms('product_cat', $query);
                            //var_dump($taxonomies);
                            ?>
                            <nav class="navbar navbar-expand-lg">
                                <div class="container-fluid ps-0">
                                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                        <ul class="navbar-nav ms-0 ms-md-auto mb-2 mb-lg-0">
                                            <?php
                                            $state = 'active';
                                            foreach ($Letters as $litter) {
                                                $i = false;
                                                foreach ($taxonomies as $category) {
                                                    if ($category->name[0] == $litter) {
                                                        if ($i == false) {
                                                            $i = true;
                                            ?>
                                                            <div class="dropdown">
                                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <?php echo '' . $litter . ''; ?>
                                                                </a>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-top:-2px;">
                                                                <?php
                                                            }
                                                                ?>
                                                                <li><a class="dropdown-item" href="javascript:void(0);" data-id="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></a></li>
                                                            <?php
                                                        }
                                                    }
                                                    if ($i == true) {
                                                            ?>
                                                                </ul>
                                                            </div>
                                                    <?php
                                                    }
                                                }
                                                    ?>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="col-12 info-cat mt-3 mb-5">
                        <?php
                        $id = $taxonomies[0]->term_id;
                        //Desde la línea 68 a la línea 74 debe convertirse en una function, ya que comienza a ver código duplicado.
                        ?>
                        <div class="imgCatLab">
                            <?php
                                if ($term = get_term_by('id', $id, 'product_cat', 'description')) {
                                    $thumbnail_id = get_term_meta($id, 'thumbnail_id', true);
                                    $image = wp_get_attachment_url($thumbnail_id);
                                    echo '<img src="' . $image . '" alt="" />';
                                    echo $term->description;
                                }
                            ?>
                        </div>
                        <?php

                        $term = search_bodega();
                        $term = $term[0];

                        //Desde la línea 76 a la línea 78 debe convertirse en una function, ya que comienza a ver código duplicado.
                        echo '<div class="box-info-produts mt-5" data-aos="fade-up">';                        
                        echo do_shortcode("[products limit='8' category='" . $taxonomies[0]->name . "' columns='4' orderby='id' order='DESC' visibility='visible' attribute='bodegas' terms='".$term->slug."']");
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
<?php get_footer(); ?>