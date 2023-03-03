(function ($) {
	$(document).on('click', '.dropdown-menu .dropdown-item', function (e) {
		e.preventDefault();
		link = $(this);
		id = link.attr('data-id');
		category_name = link.text();

		$.ajax({
			url: laboratories_vars.ajaxurl,
			type: 'post',
			data: {
				action: 'laboratories_ajax_select',
				id: id,
				category_name: category_name
			},
			beforeSend: function () {
				//Aquí debería ir el spinner del loading, pero no hay implementación, se debería de hacer.
				//link.html('Cargando ...');
			},
			success: function (resultado) {
				$('.info-cat').html(resultado);
			}
		});
	});
	if ($(document).width() > 1023) {
		$(".btn.btn-secondary.dropdown-toggle,.dropdown-menu").hover(
			function () {
				$(this).addClass("show");
				$(this).attr("aria-expanded", "true");
				$(this).siblings('.dropdown-menu').addClass("show");
				$(this).siblings('.dropdown-menu').attr("data-bs-popper", "static");
			},
			function () {
				$(this).removeClass("show");
				$(this).attr("aria-expanded", "false");
				$(this).siblings('.dropdown-menu').removeClass("show");
				$(this).siblings('.dropdown-menu').attr("data-bs-popper", "");
			}
		);
	}
})($);