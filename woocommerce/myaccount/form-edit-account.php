<?php

/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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

// defined('ABSPATH') || exit;

// do_action('woocommerce_before_edit_account_form');

$customer = new WC_Customer( get_current_user_id() );

?>
<div class="row">
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('First name', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo esc_attr($user->first_name); ?></div>
	</div>
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('Last name', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo esc_attr($user->last_name); ?></div>
	</div>
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('Email address', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo esc_attr($user->user_email); ?></div>
	</div>
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('Razón Social', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo $customer->get_billing_company(); ?></div>
	</div>
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('Sigla', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo get_field('sigla', 'user_'.get_current_user_id()); ?></div>
	</div>
	<div class="col-12 col-md-4 userData mb-2">
		<div class="label fw-600 color-3"><?php esc_html_e('Teléfono', 'woocommerce'); ?>:</div>
		<div class="datalabel color-3"><?php echo $customer->get_billing_phone(); ?></div>
	</div>
</div>

<div id="updateRequest" class="text-end">		
	<a class="btnRequest btn btn-primary my-4" href="<?php if( get_field('boton_solicitud_de_actualizacion_de_datos','option') ): the_field('boton_solicitud_de_actualizacion_de_datos','option'); endif; ?>">Actualización de Datos</a>
</div>

<legend class="fs--5 color-1 fw-600 my-4">Cambio de contraseña</legend>

<form id="changePass">
	<div class="mb-3">
		<label for="oldPassword" class="form-label color-3">Contraseña actual</label>
		<input type="password" class="form-control" id="oldPassword">
	</div>
	<div class="mb-3">
		<label for="newPassword1" class="form-label color-3">Nueva contraseña</label>
		<input type="password" class="form-control" id="newPassword1">
	</div>
	<div class="mb-3">
		<label for="newPassword2" class="form-label color-3">Confirmar nueva contraseña</label>
		<input type="password" class="form-control" id="newPassword2">
	</div>
	<button type="button" class="sendPass btn btn-primary"><?php esc_html_e('Actualizar contraseña', 'woocommerce'); ?></button>
</form>

<span id="mprueba"class="fs--6 fw-500 color-1"></span>

<!-- <?php do_action('woocommerce_after_edit_account_form'); ?> -->