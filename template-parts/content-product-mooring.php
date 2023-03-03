<?php
    $product_mooring = get_query_var('product_mooring');

    if ($args['counter'] == 1) {
?>
        <style>
            .product-mooring-item .box-img-item-mooring {
                max-width: 120px;
                margin-right: 10px;
            }

            .product-mooring-item h3 {
                font-size: 1rem;
            }
            .box-simbol-polus .simbol-plus {
                font-size: 1.5rem;
                line-height: 1rem;
            }
        </style>
<?php
    }

    $acf_product_morning = $args['acf_product_morning'];
?>

<div class="product-mooring-item d-flex <?= $acf_product_morning["producto_bonificado"] ? "free" : ""; ?>">
    <?php $product_mooring = $args['product_mooring']; ?>
    <?php
        $thumbnail_id = $product_mooring->get_image_id();
        $thumbnail_url = wp_get_attachment_url($thumbnail_id);

        $url = site_url();
        if (!file_exists( $thumbnail_url )) {
            $thumbnail_url = $url.'/wp-content/uploads/woocommerce-placeholder-416x416.png';
        }

        if($acf_product_morning["producto_bonificado"]) {

        ?>
            <label class="label-free-mooring">Bonificado</label>
        <?php

        }
    ?>
    
    <div class="box-img-item-mooring">
        <img src=<?php echo $thumbnail_url; ?>>
    </div>
    <div class="box-detail-item-mooring d-flex align-items-start justify-content-center flex-column">
        <h3 class="text-center color-3"><strong><?php echo $product_mooring->get_name(); ?></strong></h3>
        <div class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_color">
                <span class="woocommerce-product-attributes-item__label color-3 fw-500">Cantidad: </span>
                <!-- <span class="woocommerce-product-attributes-item__label"><strong>Cantidad: </strong></span> -->
                <span class="woocommerce-product-attributes-item__value"><?= $acf_product_morning["cantidad"] ?></span>
        </div>
        <!-- <div class="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_color">
                <span class="woocommerce-product-attributes-item__label"><strong>Descripción: </strong></span>
                <span class="woocommerce-product-attributes-item__value"><?= $acf_product_morning["descripcion_item_amarre"] ?></span>
        </div> -->
    </div>
</div>

    <?php
        /* if ($args['counter'] < $args['total_items']) {
    ?>
            <div class='box-simbol-polus'>
                <span class='simbol-plus color-1'>+</span>
            </div>
    <?php
        } */
    ?>