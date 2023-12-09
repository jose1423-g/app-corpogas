$(document).ready(function() {

	$("#btn-save").on('click', function (event) {
		var parametros = $('#form-add').serialize();
		parametros = parametros + "&op=save";
		$.ajax({
				type: "POST",
				url: "../../ria/nueva_solicitud.save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						// toastr.success(data.msg);
						$("#spinner").removeClass('d-none')
						setTimeout(agregar_refacciones, 1000)
					} else {
						if (result == -1) {
							toastr.warning(data.msg);
						} else {
							toastr.info(data.msg);
						}
					}
				}
		});
		// window.location.href = "../views/agregar_refacciones.php";
		event.preventDefault();
	})

	function  agregar_refacciones() {
		window.location.href = "../views/agregar_refacciones.php";
	}

	$("#cancelar").on('click', function () {
		window.location.href = "../views/index.php";
	})

} );