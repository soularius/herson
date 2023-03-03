<?php

//Insertar Javascript js y enviar ruta admin-ajax.php
add_action('wp_enqueue_scripts', 'dcms_insertar_js_myaccount');

function dcms_insertar_js_myaccount()
{

    if (!is_page('mi-cuenta')) return;

    wp_register_script('dcms_miscript_myaccount', get_stylesheet_directory_uri() . '/template-parts/myaccount/js/script.js', array('jquery'), '1', true);
    wp_enqueue_script('dcms_miscript_myaccount');

    wp_localize_script('dcms_miscript_myaccount', 'dcms_vars_myaccount', ['ajaxurl' => admin_url('admin-ajax.php')]);
}

//Devolver datos a archivo js
add_action('wp_ajax_nopriv_dcms_ajax_myaccount', 'dcms_enviar_contenido_myaccount'); //Usuarios logueados
add_action('wp_ajax_dcms_ajax_myaccount', 'dcms_enviar_contenido_myaccount'); //Usuarios no logueados

function dcms_enviar_contenido_myaccount()
{
    $oldPassword = $_POST['oldPassword'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    $user = wp_get_current_user();
    if (!($user && wp_check_password($oldPassword, $user->data->user_pass, $user->ID))) {
        echo 'La contraseña actual no es correcta.';
        wp_die();
    }

    if (strlen($newPassword1) < 8 || strlen($newPassword2) < 8) {
        echo 'La contraseña nueva debe tener como mínimo 8 carácteres.';
        wp_die();
    }

    if (!($newPassword1 === $newPassword2)) {
        echo 'Las contraseñas no son iguales.';
        wp_die();
    }

    wp_set_password($newPassword1, $user->ID);
    echo 'Tu nueva contraseña ha sido establecida con éxito.';

    wp_die();
}
