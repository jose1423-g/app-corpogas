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
			// null, //  6
			// null, //  7
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
					$('#table-estaciones').DataTable().ajax.reload();
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
		$("#button-show-sel").removeClass('active')
		$("#button-show-all").addClass('active')
		$('#IdUsuario').val('-1');
		$("#s_is_show_all").val(1)
		$('#op').val();		
		$('#DataModal').modal('show');
		$('#passwd').prop('readonly', false);
		$('#table-estaciones').DataTable().ajax.reload();
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
					$('#IdUsuario').val(data.idusuario);					
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
		// $("#EmailSupervisor").val('');
		$("#EsActivo").prop('checked', true);
	}


	$('#UsuarioPerfilId_fk').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	/* muestra todos las estaciones */
	$("#button-show-all").on('click', function () {
		$("#s_is_show_all").val(1)
		$("#button-show-sel").removeClass('active')
		$("#button-show-all").addClass('active')
		$('#table-estaciones').DataTable().ajax.reload();

	})

	/* muestra todos las estaciones seleccionadas */
	$("#button-show-sel").on('click', function () {
		$("#button-show-all").removeClass('active')
		$("#button-show-sel").addClass('active')
		$("#s_is_show_all").val(0)
		$('#table-estaciones').DataTable().ajax.reload();
	})

	let table_clientes = $('#table-estaciones').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
        // "serverSide": true,
		"searching": true,
		"ajax": {
			"url": "../../ria/usuarios_save.ria.php",
			"data": function(d) {
				d.IdUsuario = $('#IdUsuario').val();
				d.s_is_show_all = $('#s_is_show_all').val();
				d.op = $('#op').val();
	
			}
		},
		"language": {
			"url": "../../vendor/datatables/lang/Spanish.json"
		},
		"bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
		"pageLength": 5,
		"lengthMenu": [[5, 10, 15, 20, 25, 30, 100, 200, 300, 500], [5, 10, 15, 20, 25, 30, 100, 200, 300, 500]],
		"columnDefs": [
			{
				"targets": [ 0 ],
				"visible": false,
				"searchable": false,
			},
			{
				"targets": [ 1 ],
				"className": 'text-center'
			}
		],
		"columns": [
			{ "data": "IdEstacion" },
			{ "data": "Sel" },
			{ "data": "EstacionServicio"},
			{ "data": "NoEstacion" }
		],
		"order": [[ 2, "asc" ]]
	});	

	$('#table-estaciones').DataTable().ajax.reload();


	$('#table-estaciones tbody').on('click', '.idestacion', function(){

		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		var id_estacion = $(this).attr('data-id');

		var check_app = ($(this).prop('checked') ) ? 1 : 0;

		if(check_app){
			$.ajax({
				type: "POST",
				url: "../../ria/usuarios_save.ria.php",
				data:{
					IdUsuario: $("#IdUsuario").val(),
					IdEstacion_fk: id_estacion,
					check_app: check_app,
					op:'savePerfil'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							// toastr.success(data.msg);
							$('#table-estaciones').DataTable().ajax.reload();
						} else {
							toastr.warning(data.msg);
						}
				}
			});
		} else {
			$.ajax({
				type: "POST",
				url: "../../ria/usuarios_save.ria.php",
				data:{
					IdUsuario: $("#IdUsuario").val(),
					IdEstacion_fk: id_estacion,
					check_app: check_app,
					op:'savePerfil'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							// toastr.success(data.msg);
							$('#table-estaciones').DataTable().ajax.reload();
						} else {
							toastr.warning(data.msg);
						}
				}
			});
		}	
	});

	$("#btn-close").on('click', function () {		
		$('#DataModal').modal('hide');
		$('#IdUsuario').val('-1');
		$("#s_is_show_all").val(0)
		$('#op').val();		
		$("#button-show-all").removeClass('active')
		$("#button-show-sel").addClass('active')
		$('#table-estaciones').DataTable().ajax.reload();
	})

	$("#btn-x-close").on('click', function () {		
		$('#DataModal').modal('hide');
		$('#IdUsuario').val('-1');
		$("#s_is_show_all").val(0)
		$('#op').val();		
		$("#button-show-all").removeClass('active')
		$("#button-show-sel").addClass('active')
		$('#table-estaciones').DataTable().ajax.reload();
	})

} );