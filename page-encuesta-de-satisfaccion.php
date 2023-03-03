<?php get_header(); ?>
</div>
<div class="mainContainer">

<?php 
// $param = $_GET['param'];                        
$param = $_GET['poll'];
$decodeTokenB64 = base64_decode($param);
$porcion = explode(":::", $decodeTokenB64);
$order_id = $porcion[0];
$token = $porcion[1];
$btnAccess = "/mi-cuenta/?poll=". $param;

$order = wc_get_order( $order_id );
$userCurrent = wp_get_current_user();

if($userCurrent->id != $order->get_user_id()) {
    // echo "Ups!!! Algo fallo<br>";
    ?>
        <div class="boxTextRta">
            <main>
                <section id="frmPoll" class="mt-4 mb-5">
                    <div class="container">
                        <div class="row">                
                            <div class="col">
                                <h1 class="fs--3 fw-900 color-1 mb-4">Encuesta de Satisfacción</h1>                                                              
                                <?php                                     
                                    if ( is_user_logged_in() ) {
                                        if( get_field('mensaje_encuesta_usuario_incorrecto', 'option') ): 
                                            the_field('mensaje_encuesta_usuario_incorrecto','option'); 
                                        endif;
                                    } 
                                ?>                                
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    <?php
get_footer();
return false;
}
?>

<main>
    <section id="frmPoll" class="mt-4 mb-5">
        <div class="container">
            <div class="row">                
                <div class="col">
                    <h1 class="fs--3 fw-900 color-1 mb-4">Encuesta de Satisfacción</h1>
                    <?php 
                        

                        $salt = "Gr3atFuror3";
                        $name = $order->get_billing_first_name();
                        $lastname = $order->get_billing_last_name();

                        $contructWord = $order_id.",".$order->get_user_id().",".$name.",".$lastname.",".$salt;
                         if(password_verify($contructWord, $token)) {                                

                                $checkPoll = get_field( "checkPoll", $order_id);    
                               
                                // var_dump($checkPoll); 
                                // echo $checkPoll;                                                                      
                                if (!$checkPoll) {                                    
                                    ?>

                                    <div id="primary" class="content-area">
                                        <div id="content" class="site-content" role="main">
                                        <?php while ( have_posts() ) : the_post(); ?>
                                        <?php 
                                        acf_form_head();                   
                                                              
                                        acf_form(array(
                                            'post_id'       => $order_id,
                                            'post_title'    => false,
                                            'post_content'  => false                                                
                                            // 'submit_value'  => __('Enviar'),
                                            // 'return' => home_url()
                                        ));
                                        // Se actualiza el campo de formulario completado
                                        $campo1 = get_field( "pregunta_1", $order_id);    
                                        $campo2 = get_field( "pregunta_2", $order_id);    
                                        $campo3 = get_field( "pregunta_3", $order_id);    
                                        $campo4 = get_field( "pregunta_4", $order_id);    
                                        
                                        if ($campo1 != null && $campo2 != null && $campo3 != null && $campo4 != null ) {
                                            update_field('checkPoll', 1, $order_id);                                                
                                        }
                                        endwhile; 
                                        
                                        ?>
                                        </div><!-- #content -->
                                    </div><!-- #primary -->
                                    
                                    <?php                                                                                
                                }
                                else {
                                    ?>
                                    <div class="boxTextRta">
                                        <?php if( get_field('mensaje_encuesta_completada', 'option') ): 
                                            the_field('mensaje_encuesta_completada','option'); 
                                        endif; ?>                                                                       
                                    </div>
                                    <?php
                                }
                             // Token correcto
                             // Llamo a la encuesta
                            //  echo "Token correcto<br>";                             
                         }
                         else {
                             // Token fallo
                             // Muestro mensaje de permiso invalido
                             echo "Token incorrecto<br>";
                             // return;
                         }                         
                    ?>                                    
                </div>
            </div>            
        </div>
    </section>    
</main>

<?php get_footer();?>