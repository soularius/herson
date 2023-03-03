(function ($) {

    $(document).on('click', '.more-link', function (e) {
        e.preventDefault();
        a = $('#a').val();
        b = $('#b').val();

        $.ajax({
            url: dcms_vars.ajaxurl,
            type: 'post',
            data: {
                action: 'dcms_ajax_readmore',
                a: a,
                b: b,
            },
            beforeSend: function () {
                console.log('Estamos procesando la petición ...');
            },
            success: function (resultado) {
                console.log('La petición arrojó: ' + resultado);
                $('#resultado').html(resultado);
            }

        });

    });

})(jQuery);