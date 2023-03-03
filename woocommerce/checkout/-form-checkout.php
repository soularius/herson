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
        <!-- <?php do_action('woocommerce_checkout_before_customer_details'); ?> -->
        <div class="col2-set jaav" id="customer_details">
            <div class="col-1">
                <!-- <?php do_action('woocommerce_checkout_billing'); ?> -->
                <?php
                // $current_user = wp_get_current_user();
                ?>
                <div class="dataCheckout mb-3">
                    <div class="ttlItemDataUser fw-600"><?php esc_html_e('First name', 'woocommerce'); ?></div>
                    <div class="itemDataUser"><?php echo $current_user->user_firstname; ?></div>
                </div>
                <div class="dataCheckout mb-3">
                    <div class="ttlItemDataUser fw-600"><?php esc_html_e('Last name', 'woocommerce'); ?></div>
                    <div class="itemDataUser"><?php echo $current_user->user_lastname; ?></div>
                </div>
                <div class="dataCheckout mb-3">
                    <div class="ttlItemDataUser fw-600"><?php esc_html_e('Email address', 'woocommerce'); ?></div>
                    <div class="itemDataUser"><?php echo $current_user->user_email; ?></div>
                    <div class="itemDataUser"><?php echo $checkout->first_name; ?></div>
                </div>
                <?php
                // $sub_field = get_field_object($checkout[0]));
                //var_dump($checkout->fields);
                echo '<br><br>';
                //var_dump($checkout);
                var_dump($checkout->get_value('billing'));
                echo '<br><br>';
                echo '<br><br>';
                echo $checkout->get_value('billing')['first_name'];
				
                ?>
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