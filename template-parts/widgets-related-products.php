<?php
// Archivos Relacionados
function dc_related_after_content( $content ) 
{ 
   
   if ( !is_singular('post') ) return $content;	
   
   $cad			= "";
   $template_li 	= '<div class="col-12 col-md-4 col-lg-3">
                           <div class="thumbPost mb-3">
                               <a class="thumb_rel" href="{url}">{thumb}</a>
                           </div>
                           <div class="namePost">
                               <h4><a class="text-decoration-none fw-600 color-3" class="title_rel" href="{url}">{title}</a></h4>
                           </div>
                           <div class="excertpPost">
                               <p>{excertp}</p>								
                           </div>
                           <div class="seeMoreBtn">
                               <a class="seeMore_rel text-decoration-none color-4" href="{url}">Ver más...</a>
                           </div>
                       </div>';
   $template_rel	= '<div class="rel_posts mt-4">
                           <h3 class="fs--3 color-1 fw-600 mb-4">Artículos Relacionados</h3>
                           <div class="container">
                               <div class="row">
                                   {list}
                               </div>
                           </div>
                      </div>';

   $terms = get_the_terms( get_the_ID(), 'category');
   $categ = array();
   
   if ( $terms )
   {
       foreach ($terms as $term) 
       {
           $categ[] = $term->term_id;
       }
   }
   else{
       return $content;
   }

   $loop	= new WP_QUERY(array(
                   'category__in'		=> $categ,
                   'posts_per_page'	=> 4,
                   'post__not_in'		=>array(get_the_ID()),
                   'orderby'			=>'rand'
                   ));

   if ( $loop->have_posts() )
   {

       while ( $loop->have_posts() )
       {
           $loop->the_post();

           $search	 = Array('{url}','{thumb}','{title}', '{excertp}');
           $extract =  substr(get_the_content(), 0, 80).'...';
             $replace = Array(get_permalink(),get_the_post_thumbnail(),get_the_title(),$extract);
       
           $cad .= str_replace($search,$replace, $template_li);
       }

       if ( $cad ) 
       {
             $content .= str_replace('{list}', $cad, $template_rel);
       }

   }
      wp_reset_query();

   return $content;
}

add_filter( 'the_content', 'dc_related_after_content'); 	
?>