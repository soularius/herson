</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action('storefront_before_footer'); ?>

<?php
content_to_logged_footer();
?>

<?php do_action('storefront_after_footer'); ?>

<?php
custom_style_footer();
?>

</div><!-- #page -->

<div id="floating_buttons" class="row">
	<?php
	// Check rows exists.
	if (have_rows('botones_flotantes', 'option')) :
		// Loop through rows.
		while (have_rows('botones_flotantes', 'option')) : the_row();
			// Load sub field value.
	?>
			<div class="col item-btn-fixed">
				<a href="<?php if (get_sub_field('url_bnt_flotante', 'option')) : the_sub_field('url_bnt_flotante', 'option');
							endif; ?>" target="_blank">
					<?php
					$image = get_sub_field('icono_btn_flotante', 'option');
					$size = 'full'; // (thumbnail, medium, large, full or custom size)
					if ($image) {
						echo wp_get_attachment_image($image, $size);
					}
					?>
				</a>
			</div>
	<?php
		// End loop.
		endwhile;
	endif; ?>
</div>

<?php wp_footer(); ?>
</body>

</html>