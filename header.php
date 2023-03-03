<?php

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?>
<?php
$url_actual = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (!is_page(get_field('lista_de_paginas_publicas', 'option')) && !is_home() && !is_singular('post')) :
	if (!is_user_logged_in()) :
		wp_redirect(get_field('link_de_redireccion', 'option') . '?redirect_to=' . $url_actual);
		exit;
	endif;
endif;
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action('storefront_before_site'); ?>

	<div id="page" class="hfeed site">
		<?php do_action('storefront_before_header'); ?>
		<div class="top-bar-header">
			<div class="container">
				<div class="row">
					<div class="col col-lg-8 fs-6 my-1 fw-400 d-none d-lg-block">
						<span>
							<?php if (get_field('mensaje_top_bar', 'options')) :
								the_field('mensaje_top_bar', 'options');
							endif; ?>
						</span>
					</div>
					<div class="col col-lg-4 fs-6 my-1 text-end text-decoration-none">
						<svg class="pe-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
							<path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
						</svg>
						<span>
							<?php
							$link = get_field('telefono_de_contacto_top_bar', 'options');
							if ($link) :
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
							?>
								<a class="text text-decoration-none color-6 topBarTel" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
							<?php endif; ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_header_container                 - 0
			 * @hooked storefront_skip_links                       - 5
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_header_container_close           - 41
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action('storefront_header');
			?>

		</header><!-- #masthead -->

		<?php
		/**
		 * Functions hooked in to storefront_before_content
		 *
		 * @hooked storefront_header_widget_region - 10
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action('storefront_before_content');
		?>


		<div id="content" class="site-content" tabindex="-1">
			<div class="col-full">
				<?php
				do_action('storefront_site_branding');
