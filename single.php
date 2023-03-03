<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			?>

			<div class="titlePost"><h1 class="fs--3 color-1 fw-900" ><?php the_title();?></h1></div>

			<p class="datePost fw-700 color-3 mb-4">Publicado el <?php the_date();?></p>
			
			<div class="imgSinglePost mb-2"><?php the_post_thumbnail();?></div>

			<div class="textImg text-end mb-4">
				<?php if( get_field('leyenda_imagen') ): 
					the_field('leyenda_imagen'); 
				endif; ?>
			</div>

			<div class="contentPost"><?php the_content();?></div>			
			
			<?php

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
