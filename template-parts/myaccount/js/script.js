(function ($) {

    $(document).on('click', '.sendPass', function (e) {
        e.preventDefault();
        oldPassword = $('#oldPassword').val();
        newPassword1 = $('#newPassword1').val();
        newPassword2 = $('#newPassword2').val();

        $.ajax({
            url: dcms_vars_myaccount.ajaxurl,
            type: 'post',
            data: {
                action: 'dcms_ajax_myaccount',
                oldPassword: oldPassword,
                newPassword1: newPassword1,
                newPassword2: newPassword2,
            },
            beforeSend: function () {
                console.log('Estamos procesando la petición ...');
            },
            success: function (mprueba) {
                // console.log('La petición arrojó: ' + mprueba);
                $('#mprueba').html(mprueba);
            }

        });

    });

})(jQuery);