<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="myAccount" class="mb-5">
        <div class="container">
            <div class="row">
                <span class="fs--4 color-1 fw-900 mb-3">Mi cuenta</span>
                <div class="col">
                    <?php 
                        mi_cuenta_show();
                    ?>
                </div>
            </div>
            <div class="zoneBk">
                <?php
                    $image = get_field('imagen_mi_cuenta');
                    $size = 'full'; // (thumbnail, medium, large, full or custom size)
                    if ($image) {
                    echo wp_get_attachment_image($image, $size);
                    }
                ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer();?>