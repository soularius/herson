<?php
    //get_template_part('sheduled/sheduled', 'sedes');
//get_template_part('ERP/connect', 'products');
//get_template_part('sheduled/sheduled', 'products2');
get_header();
?>
</div>
<div class="mainContainer">
<main>
    <section id="sedes" class="mb-5">
        <div class="container">
            <div class="row">
                <?php

                    $args = array(
                        "posts_per_page" => 20,
                        "paged" => 1,
                        "post_type" => array("product"),
                        "meta_query" => array(
                            'relation' => 'OR',
                            array(
                                "key" => "_sku",
                                "value" => "Amoxicilina",
                                "compare" => "="
                            ),
                            array(
                                "key" => "post_title",
                                "value" => "Amoxicilina",
                                "compare" => "LIKE"
                            ),
                        )
                    );

                    // The Query
                    $query = new WP_Query( $args );

                    echo "<pre>";
                    var_dump($query);
                    echo "</pre>";

                ?>
            </div>
        </div>
    </section>
</main>

<?php

?>
<?php get_footer();?>