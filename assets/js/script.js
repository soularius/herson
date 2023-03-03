(function ($) {
    // document.getElementById("SlderItem1").classList.add ("active");

    if (screen.width < 768) {
        $('#menu-menu-usuarios li a').text('');
        $('#menu-menu-usuarios-logueados li a').text('');
        $('#menu-menu-favoritos li a').text('');
    }

    AOS.init();

    $(document).ready(function () {
        $("#lightSlider-testimonios").lightSliderTestimonial();
        $("#lightSlider-quickoptions").lightSlider();
        $("#lightSlider-aliados").lightSliderAllies();
    });
})(jQuery);

// Inhabilita el botón de Enviar en el formulario de Encuestas
window.onload = (event) => {
    var element = document.getElementById('frmPoll');

    // Validar existencia del elemento botón y deshabilitarlo
    if (typeof (element) != 'undefined' && element != null) {
        var elements = document.getElementsByClassName("acf-button");
        elements[0].className += " disable";
    }

    document.getElementById("pregunta_1").addEventListener("change", encuestaCompletada);
    document.getElementById("pregunta_2").addEventListener("change", encuestaCompletada);
    document.getElementById("pregunta_3").addEventListener("change", encuestaCompletada);
    document.getElementById("pregunta_4").addEventListener("change", encuestaCompletada);


    // const innerDiv = document.querySelector('#pregunta_1').querySelector('.selected');
    // console.log(innerDiv);

    function encuestaCompletada() {
        var x1 = document.getElementsByClassName("acf-field-radio selected");
        console.log(x1);
    }    
};


jQuery(document).ready(function ($) {
    $('.add-to-item-cart').submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();
        let div_msg = form.next();
        div_msg.innerHTML = "";


        // Deshabilitar el botón y agregar el spinner
        let button = form.find('input[type="submit"]');
        button.prop('disabled', true);
        button.after('<span class="spinner"></span>');

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (response) {
                let errorMessage = jQuery(response).find('.woocommerce-error');
                //let errorMessage = jQuery(response).find('.woocommerce-error').text();
                if (errorMessage.length) {
                    console.log("Error");
                    for (let i = 0; i < errorMessage.length; i++) {
                        // Agregar mensaje de éxito debajo del botón
                        let message = '<div class="woocommerce-error">' + errorMessage[i].innerHTML + ' <a href="#" class="remove-message"> (quitar)</a></div>';
                        div_msg = div_msg[0];
                        div_msg.innerHTML = message;
                    }

                }
                else {
                    console.log("Success");
                    // Agregar mensaje de éxito debajo del botón
                    let message = '<div class="success-message woocommerce-message">' +
                        '<a href="' + wc_add_to_cart_params.cart_url + '" tabindex="1" class="button wc-forward wp-element-button">Ver carrito</a>' +
                        'Producto agregado al carrito <a href="#" class="remove-message"> (quitar)</a></div > ';

                    div_msg = div_msg[0];
                    div_msg.innerHTML = message;
                }
                update_cart_info_callback();
            },
            error: function (error) {
                console.log(error);
            },
            complete: function (jqXHR) {
                // Habilitar el botón y eliminar el spinner
                button.prop('disabled', false);
                form.find('.spinner').remove();
            }
        });
    });

    function update_cart_info_callback() {
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
              action: 'update_cart_info'
            },
            success: function(response) {
                let cartInfo = response.split('|');
                let cartCount = cartInfo[0];
                let cartTotal = cartInfo[1];
                // Actualizar el número de elementos en el carrito en el icono
                $('.cart-contents .count').text(cartCount);
                // Actualizar el valor total del carrito en el icono
                $('.cart-contents .amount').replaceWith(cartTotal);
                console.log(cartTotal);
            }
          });
    }
});