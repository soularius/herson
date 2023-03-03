<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">				
				<?php do_action('woocommerce_checkout_billing'); ?>

				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Nombres</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['first_name']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Apellidos</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['last_name']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Nombre de la empresa</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['company']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">País / Región</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['country']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Dirección de la calle</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['address_1']; ?> - <?php echo $checkout->get_value('billing')['address_2']; ?></p>
				</div>				
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Población </span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['city']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Departamento </span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['state']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Código postal</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['postcode']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Teléfono </span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['phone']; ?></p>
				</div>
				<div class="itemDetails">
					<span class="label fs--7 color-3 fw-500">Dirección de correo electrónico</span>
					<p class="color-3"><?php echo $checkout->get_value('billing')['email']; ?></p>
				</div>
				<div id="updateRequest">		
					<a class="btnRequest btn btn-primary my-4" href="<?php if( get_field('boton_solicitud_de_actualizacion_de_datos','option') ): the_field('boton_solicitud_de_actualizacion_de_datos','option'); endif; ?>">Actualización de Datos</a>
				</div>
			</div>

			<div class="col-2">
				<?php do_action('woocommerce_checkout_shipping'); ?>
			</div>
		</div>

		<?php do_action('woocommerce_checkout_after_customer_details'); ?>

	<?php endif; ?>

	<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

	<h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>

	<?php do_action('woocommerce_checkout_before_order_review'); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>

	<?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>