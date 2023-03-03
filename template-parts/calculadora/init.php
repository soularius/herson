<?php

//Insertar Javascript js y enviar ruta admin-ajax.php
add_action('wp_enqueue_scripts', 'dcms_insertar_js');

function dcms_insertar_js()
{

    if (!is_page('ajax')) return;

    wp_register_script('dcms_miscript', get_stylesheet_directory_uri() . '/template-parts/calculadora/js/script.js', array('jquery'), '1', true);
    wp_enqueue_script('dcms_miscript');

    wp_localize_script('dcms_miscript', 'dcms_vars', ['ajaxurl' => admin_url('admin-ajax.php')]);
}

//Devolver datos a archivo js
add_action('wp_ajax_nopriv_dcms_ajax_readmore', 'dcms_enviar_contenido'); //Usuarios logueados
add_action('wp_ajax_dcms_ajax_readmore', 'dcms_enviar_contenido'); //Usuarios no logueados

function dcms_enviar_contenido()
{
    $a = $_POST['a'];
    $b = $_POST['b'];
    //$clave_antigua = $_POST['clave_antigua'];
    //$clave_nueva_1 = $_POST['clave_nueva_1'];
    //$clave_nueva_2 = $_POST['clave_nueva_2'];

    /*if(!$clave_nueva_1 === $clave_nueva_2){
        echo 'Las constraseñas no son iguales';
        wp_die();
    }*/
    $user = wp_get_current_user();
    //var_dump($user);
    if ($user && wp_check_password($a, $user->data->user_pass, $user->ID)) {
        echo "That's it";
    } else {
        echo "Nope";
    }
    //Validación #2 ... Validar si la clave antigua es verdadera ... $clave_antigua ... No es verdadera mata el proceso, sino sigue.

    //Usar function para definir contraseña nueva ... $clave_nueva_1 ... Enviar resultado de cambio contraseña éxitoso

    $resultado = $a + $b;

    echo $resultado;

    wp_die();
}
