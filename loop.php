<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package storefront
 */

// do_action( 'storefront_loop_before' );
?>
<div class="container my-5" data-aos="zoom-in-up">
	<div class="row">
		<!-- <div class="col-12 col-lg-3">

		</div> -->
		<!-- <div class="col-12 col-lg-9"> -->
		<div class="col">
			<div class="row">
				<?php
					while ( have_posts() ) :
						the_post();						
						?>

						<div class="itemPostList col-12 col-sm-6 col-md-4 mb-5">
							<div class="thumbnailPost mb-3 col-a"> <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail();?></a></div>
							<div class="col col-b">
								<div class="titlePost"><h4 class="fs--6"><a class="text-decoration-none fw-600 color-3" href="<?php echo get_permalink();?>"><?php the_title();?></a></h4></div>
								<?php								
								$extract =  substr(get_the_content(), 0, 85);
								?>
								
								<div class="contentPost"><?php echo $extract.'...'; ?></div>
								<div class="seeMoreBtn"><a class="text-decoration-none color-4" href="<?php echo get_permalink();?>">Ver más...</a></div>
							</div>
						</div>

						<?php

					endwhile;
				?>
			</div>
		</div>
	</div>
</div>
<?php
/**
 * Functions hooked in to storefront_paging_nav action
 *
 * @hooked storefront_paging_nav - 10
 */

do_action( 'storefront_loop_after' );