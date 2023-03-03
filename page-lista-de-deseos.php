<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="wishList" class="mb-5">
        <div class="container">
            <div class="row">                
                <div class="col">
                    <div class="fs--4 color-1 fw-900 mb-3">Mi lista de deseos</div>
                    <div class="boxWishList">
                        <?php 
                            echo do_shortcode("[yith_wcwl_wishlist]");
                        ?>
                    </div>
                </div>
            </div>            
        </div>
    </section>
</main>
<?php get_footer();?>