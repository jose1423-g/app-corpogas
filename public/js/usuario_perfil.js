$(document).ready(function() {
	
	alert("si jalo")

	$('#passwd_confirma').keydown( function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			$('#button-save').click();
			return false;
		}
	});

	$('#button-save').on('click', function(){
		$.ajax({
				type: "POST",
				url: "../ria/usuario-perfil-save.ria.php",
				data: {
					id_usuario: $("#IdUsuario").val(),
					passwd_actual: $("#passwd_actual").val(),
					passwd_nuevo: $("#passwd_nuevo").val(),
					passwd_confirma: $("#passwd_confirma").val(),
					op: 'changePwd'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						$('#DataModal').modal('hide');
					} else {
						if (result == -1) {
							toastr.warning(data.msg);
						} else {
							toastr.info(data.msg);
						}
					}
				
			  }
		});
	  event.preventDefault();
		
	});
	
	$('#DataModal').on('hidden.bs.modal', function (e) {
		$("#passwd_actual").val('');
		$("#passwd_nuevo").val('');
		$("#passwd_confirma").val('');
	})
	
} );