<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="detailsOrder" class="">
        <div class="container">
            <div class="row">
                <span class="fs--4 color-1 fw-900 mb-3">Detalle del Pedido</span>
                <div class="col">
                    <?php 
                        carrito_show();
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .box-simbol-polus {
        margin: 0 8px;
    }
    .tittle-mooring {
        margin-top: 10px;
    }
</style>
<?php get_footer();?>