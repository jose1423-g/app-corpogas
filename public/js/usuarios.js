$(document).ready(function() {
	// mensajes especificos
	var confirmacion_elimina = '¿Desea eliminar este usuario?'; // ADHOC (no se deben eliminar usuarios)

    var table = $('#grid-table').DataTable( {
        "responsive": true,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
        "ajax": "../../ria/usuarios.ria.php", // ADHOC
        "language": {
            "url": "../../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
			{
				"targets": [ 1 ],
				"className": 'text-center'
			}
        ],
		"order": [[0, "asc"]],
		"columns": [
			null, //  0
			null, //  1
			null, //  2
			null, //  3
			null, //  4
			null, //  5
			null, //  6
			null, //  7
		]
    });


	$('#grid-table tbody').on( 'click', '.btn-edit', function () {
		$('#DataModal').modal('show');
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		let id_usuario = data[0];
		$("#IdUsuario").val(id_usuario);
		$('#passwd').prop('readonly', true);
		loadusuario(id_usuario)
	} );


	function loadusuario(id_usuario) {
		
		$.ajax({
            type: "POST",
            url: "../../ria/usuarios_save.ria.php",
            data: {
                IdUsuario: id_usuario,
                op: 'loadUsuario'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					// toastr.success(data.msg);
					$("#UserName").val(data.Username);
					$("#Nombre").val(data.Nombre);
					$("#ApellidoPaterno").val(data.ApellidoPaterno);
					$("#ApellidoMaterno").val(data.ApellidoMaterno);
					$("#passwd").val(data.passwd);
					$('#UsuarioPerfilId_fk').val(data.UsuarioPerfilId_fk).trigger('change');
					$("#Email").val(data.Email);
					$("#telefono").val(data.telefono);
					$('#IdEstacion_fk').val(data.IdEstacion_fk).trigger('change');
					$("#EmailSupervisor").val(data.EmailSupervisor);
					
					// $("#EsActivo").val(data.EsActivo);
					if (data.EsActivo == 1) {
						$('#EsActivo').prop('checked', true);
					} else {
						$('#EsActivo').prop('checked', false);
					}
				} else {
					if (result == -1) {
						toastr.warning(data.msg);
					} else {
						toastr.info(data.msg);
					}
				}
            }
        });
	}

	$("#btn-add").on('click', function () {
		$('#DataModal').modal('show');
		$('#passwd').prop('readonly', false);
		clear();
	})

	$("#btn-save").on('click', function () {
		let  parametros = $("#form-data").serialize();
		parametros = parametros+"&op=save";
		$.ajax({
            type: "POST",
            url: "../../ria/usuarios_save.ria.php",
            data: parametros,
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					toastr.success(data.msg);
					$('#grid-table').DataTable().ajax.reload();
				} else {
					if (result == -1) {
						toastr.warning(data.msg);
					} else {
						toastr.info(data.msg);
					}
				}
            }
        });
		
	})

	function clear() {
		$("#IdUsuario").val('');
		$("#UserName").val('');
		$("#Nombre").val('');
		$("#ApellidoPaterno").val('');
		$("#ApellidoMaterno").val('');
		$("#passwd").val('');
		// $('#UsuarioPerfilId_fk').val('')
		$('#UsuarioPerfilId_fk').val(null).trigger('change');
		$("#Email").val('');
		$("#telefono").val('');
		// $('#IdEstacion_fk').val('');
		$('#IdEstacion_fk').val(null).trigger('change');
		$("#EmailSupervisor").val('');
		$("#EsActivo").prop('checked', true);
	}


	$('#UsuarioPerfilId_fk').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	$('#IdEstacion_fk').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});


	
	
	// $('#button-add').on('click', function(){
	// 	$('#DataModal').modal('show');
		
	// 	// clean form
	// 	$("#IdUsuario").val('');
	// 	$("#UserName").val('');
	// 	$("#ApellidoPaterno").val('');
	// 	$("#ApellidoMaterno").val('');
	// 	$("#Nombre").val('');
	// 	$("#UsuarioPerfilId").val('');
	// 	$("#passwd").val('');
	// 	$("#IsPasswdMod").val('0');
	// 	$('#EsActivo').prop('checked', true);
	// });
	
	// $('#button-save').on('click', function(){
	// 	var parametros = $('#form-data').serialize();
	// 	$.ajax({
	// 			type: "POST",
	// 			url: "../../ria/usuarios-save.ria.php",
	// 			data: parametros,
	// 			success: function(data){
	// 				var data = jQuery.parseJSON(data);
	// 				var result = data.result;
	// 				if (result == 1) {
	// 					toastr.success(data.msg);
	// 				} else {
	// 					if (result == -1) {
	// 						toastr.warning(data.msg);
	// 					} else {
	// 						toastr.info(data.msg);
	// 					}
	// 				}
				
	// 			$('#DataModal').modal('hide');
	// 			$('#grid-table').DataTable().ajax.reload();
	// 		  }
	// 	});
	//   event.preventDefault();
		
	// });

	// $('#grid-table tbody').on( 'click', '.button-delete', function () {
	// 	if (!confirm(confirmacion_elimina)) return false;
		
	// 	var data = table.row($(this).parents('tr')).data();
	// 	if (data == undefined) {
	// 		data = table.row( this ).data();
	// 	}
	// 	parametros = 'IdUsuario=' + data[0] + '&op=del';
	// 	$.ajax({
	// 			type: "POST",
	// 			url: "../../ria/usuarios-save.ria.php",
	// 			data: parametros,
	// 			success: function(data){
	// 				var data = jQuery.parseJSON(data);
	// 				var result = data.result;
	// 				if (result == 1) {
	// 					toastr.success(data.msg);
	// 				} else {
	// 					if (result == -1) {
	// 						toastr.warning(data.msg);
	// 					} else {
	// 						toastr.info(data.msg);
	// 					}
	// 				}
				
	// 			$('#DataModal').modal('hide');
	// 			$('#grid-table').DataTable().ajax.reload();
	// 		  }
	// 	});
	//   event.preventDefault();
	// } );
} );