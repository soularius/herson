<?php get_header(); ?>
</div>
<div class="mainContainer">
<main>
    <section id="legalDocument" class="mb-5">
        <div class="container">
            <div class="row">                
                <div class="col">
                    <h1 class="fs--3 color-1 fw-900 mb-5">
                        <?php if( get_field('titulo') ): 
                            the_field('titulo'); 
                        endif; ?>
                    </h1>                    
                    <div class="container p-0">
                        <?php if( get_field('contenido') ): 
                            the_field('contenido'); 
                        endif; ?>
                    </div>
                </div>
            </div>            
        </div>
    </section>
</main>
<?php get_footer();?>