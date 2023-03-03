<?php

function function_destacados_post(){	
	?>
	<div class="boxSideBar mt-5">	
		<div class="col ttleSideBar mb-3 pt-3">
			<h3 class="fs--5 color-1 fw-600 px-3">Te puede interesar</h3>
		</div>
		<div class="col px-3 pb-3">
			<?php		
			// Define our WP Query Parameters
			$the_query = new WP_Query( 'cat=45','posts_per_page=4' ); ?>
			<?php
			// Start our WP Query
			while ($the_query -> have_posts()) : $the_query -> the_post();
			// Display the Post Title with Hyperlink
			?>
			<div class="row item mb-3">
				<div class="imgThumbPost col-3 img pe-0">
					<?php the_post_thumbnail( 'thumbnail' );  ?>
				</div>
				<div class="col-9">
					<div class="col ttlPost">
						<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
					</div>
					<div class="col contPost">
						<?php
							// Display the Post Excerpt
							// the_excerpt(__('(more…)'));
							$excerpt = get_the_excerpt(); 			
							$excerpt = substr( $excerpt, 0, 45 ); // Only display first 260 characters of excerpt
							$result = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
							echo $result;
							
						?>
					</div>
					<div class="col linkPost">
						<a href="<?php the_permalink() ?>">Ver más...</a>
					</div>			
				</div>
			</div>
			<?php
			// Repeat the process and reset once it hits the limit
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<div class="formSuscribete pt-3 px-3">
			<span class="labelForm">Suscríbete</span>
			<?php
				echo do_shortcode("[ninja_form id=3]");
			?>
			<!-- <?php
				echo do_shortcode("[display-posts category='destacados']");
			?>			 -->
		</div>	
	</div>
	<?php
}
add_shortcode('destacados-post', 'function_destacados_post');

?>