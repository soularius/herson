<?php

// Email automático para cuando la orden cambia al estado de "Completada".

add_action("woocommerce_order_status_changed", "my_awesome_publication_notification");

function my_awesome_publication_notification($order_id, $checkout=null) {
   global $woocommerce;
   $order = new WC_Order( $order_id );
    
    // Captura campo ACF de Asunto del email
    if( get_field('asunto_email','option') ): 
        $customSubject = get_field('asunto_email', 'option');
    endif;

    // Captura campo ACF de Cabecera del email
    if( get_field('cabecera_email','option') ): 
        $customHeader = get_field('cabecera_email', 'option');
    endif;

    // Captura campo ACF de Texto anterior
    if( get_field('texto_anterior','option') ): 
      $customBeforeText = get_field('texto_anterior', 'option');
    endif;

    // Captura campo ACF de Texto posterior
    if( get_field('texto_posterior','option') ): 
      $customAfterText = get_field('texto_posterior', 'option');
    endif;

    // Captura campo ACF de Contenido del email
    if( get_field('contenido_email','option') ): 
        $customBody = get_field('contenido_email', 'option');
    endif; 

    // Captura campo ACF de Footer del email
    if( get_field('footer_email','option') ): 
      $customFooter = get_field('footer_email', 'option');
    endif; 
    
    // Captura campo ACF de URL encuesta
    if( get_field('url_encuesta','option') ): 
      $urlEncuesta = get_field('url_encuesta', 'option');
    endif; 

    // Captura campo ACF de Etiqueta encuesta
    if( get_field('etiqueta_link_encuesta','option') ): 
      $labelEncuesta = get_field('etiqueta_link_encuesta', 'option');
    endif; 
    
    if($order->status === 'despachado' ) {
      // Creación de un mailer
      $mailer = $woocommerce->mailer();
      $user_id = $order->get_user_id( );
      $customerFirstName = $order->get_billing_first_name();
      $customerLastName = $order->get_billing_last_name();
      $salt = "Gr3atFuror3";

      // Combinado de parámetros para la url
      $combineWord  =  $order_id.",".$user_id.",".$customerFirstName.",".$customerLastName.",".$salt;

      // Encriptado de parámetros
      $token = password_hash($combineWord, PASSWORD_BCRYPT);
      $param = base64_encode($order_id.":::".$token);

      // Armado URL final con intervinientes
      $urlCompuesta = $urlEncuesta. "?poll=" . $param;
      
      $completeBody =  "<h2><span style='color: #6387f2;'>" . $customBeforeText . $customerFirstName . $customAfterText . "</span></h2>" . $customBody . "<style> .ctaEncuesta { font-size:30px; color:#ffa500; font-weight: 700; line-height:60px }</style><center><a class='ctaEncuesta' href='" . $urlCompuesta . "' target='_blank'>" . $labelEncuesta . "</a></center>" . "<br><br>" . $customFooter;

      $message_body = __( $completeBody );
      $subject = sprintf(__( $customSubject ), $order->get_order_number());
      $email_heading = sprintf(__( $customHeader ), $order->get_order_number());

      $message = $mailer->wrap_message($email_heading, $message_body);

      $headers = "Content-Type: text/html\r\n";
      $mailer->send( $order->billing_email, $subject , $message, $headers );
      
     }
   }

?>