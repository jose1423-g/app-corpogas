$(document).ready(function() {
	// mensajes especificos
	var confirmacion = '¿Desea eliminar esta categoria?'; // ADHOC (no se deben eliminar usuarios)

	$('#datetimepicker1').datetimepicker({
		format: 'L',
		locale: 'es'
	});

    var table = $('#grid-table').DataTable( {
        "responsive": true,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
		"searching": true,
        "ajax": {
            "url":"../../ria/solicitudes_pendientes.ria.php", // ADHOC
            "data": function(d) {
                d.Folio = $('#Folio').val();
                d.s_mostrar = $('#s_mostrar').val();
            }
        },
        "language": {
            "url": "../../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
				"targets": [ 1,2,3,4,5 ],
				"className": 'text-center'
			}
        ],
        "order": [[0, "asc"]],
		"columns": [
			null, // # 0
			null, // acciones 1
			null, // folio 2
			null, // no estacion 3
			null, // estatus 5
            null, //  fecha
		] 
    });

    $("#btn-search").on('click', function () {
        $('#grid-table').DataTable().ajax.reload();
    })
 
	$('#grid-table tbody').on( 'click', '.btn-show', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        $("#id_solicitud").val(data[0])
		loadSolicitud(data[0])
	});

    function  loadSolicitud (id_solicitud) {
        $.ajax({
            type: "post",
            url: "../../ria/solicitudes_pendientes_save.ria.php",
            data: {
                id_solicitud: id_solicitud,
                op: 'loadSolicitud'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					$("#folio").text(data.Folio);
                    $("#Estatus").html(data.Estatus);
                    $("#fecha").html(data.Fecha);
                    $("#matentregados").html(data.MatEntregado);
                    $("#areasolicita").html(data.AreaSolicita);
                    $("#matcompleto").html(data.EntregoMatCompleto);
                    $("#folioremision").html(data.FolioRemision);
                    $("#observaciones").html(data.Observaciones);
                    $("#motivorechazo").html(data.MotRechazo);
                    $("#obsgenerales").html(data.ObGenerales);
                    $("#noestacion").html(data.NoEstacion);
                    $("#gerente").html(data.Gerente);
                    $("#email").html(data.Email);
                    $("#telefono").html(data.Telefono);
					$('#table-refacciones').DataTable().ajax.reload();	
                } else {
					
				}
            }
        });
    }
	
	$('#button-add').on('click', function(){
		$('#DataModal').modal('show');
		
		// clean form
		$("#id_categoria").val('');
		$("#Categoria").val('');
		$("#IdUsuario_fk").val('');
		$('#EsActivo').prop('checked', true);
	});

	var table_solicitud = $('#table-refacciones').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": false,
        "serverSide": false,
		"searching": false,
		"ajax": {
			"url": "../../ria/solicitudes_pendientes_save.ria.php", // ADHOC
			"data": function(d) {
				d.op = 'ShowProducts';
				d.id_solicitud = $('#id_solicitud').val();
				// d.= $('#s_Mostrar').val();
			}
		},
		"language": {
			"url": "../../vendor/datatables/lang/Spanish.json"
		},
		"bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
		"pageLength": 10,
		"lengthMenu": [[10, 20, 25, 50, 100, 200,500], [10, 20, 25, 50, 100, 200, 500]],
		"columnDefs": [
			{
				"targets": [ 0 ],
				"visible": true,
				"searchable": false
			},
			{
				"targets": [ 0,1,2,3 ],
				"className": 'text-center'
			}
		],
		// "order": [[3, "desc"]],
		"columns": [
			{ "data": "IdPartida" },
			{ "data": "Referencia" },
			{ "data": "NombreRefaccion" },
			{ "data": "Cantidad" }
			// { "data": "icons" }
		]
	});

	// $('#IdUsuario_fk').select2({
	// 	theme: 'bootstrap4',
	// 	dropdownParent: $('#DataModal'),
	// 	language: 'es',
	// 	allowClear: true,
	// 	placeholder: 'Selecciona un valor',
	// });

	// // $('#IdUsuario_fk').val(1).trigger('change');


	// $('#button-save').on('click', function(){
	// 	var parametros = $('#form-data').serialize();
	// 	parametros = parametros + "&op=save";
	// 	$.ajax({
	// 			type: "POST",
	// 			url: "../../ria/seg_categorias_save.ria.php",
	// 			data: parametros,
	// 			success: function(data){
	// 				var data = jQuery.parseJSON(data);
	// 				var result = data.result;
	// 				if (result == 1) {
	// 					toastr.success(data.msg);
	// 					// $('#DataModal').modal('hide');
	// 					$('#grid-table').DataTable().ajax.reload();
	// 				} else {
	// 					if (result == -1) {
	// 						toastr.warning(data.msg);
	// 					} else {
	// 						toastr.info(data.msg);
	// 					}
	// 				}
	// 			}
	// 	});
	// 	event.preventDefault();
		
	// });

	// $("#btn-delete").on('click', function () {
	// 	id_categoria =  $("#id_categoria").val();
	// 	if (!confirm(confirmacion)) return false;
	// 	$.ajax({
    //         type: "get",
    //         url: "../../ria/seg_categorias_save.ria.php",
    //         data: {
    //             id_categoria: id_categoria,
    //             op: 'delete'
    //         },
    //         success: function (data) {
    //             var data = jQuery.parseJSON(data);
	// 			var result = data.result;
	// 			if (result == 1) {
	// 				toastr.success(data.msg);
	// 				$('#DataModal').modal('hide');
	// 				$('#grid-table').DataTable().ajax.reload();
	// 			} else {
	// 				if (result == -1) {
	// 					toastr.warning(data.msg);
	// 				} else {
	// 					toastr.info(data.msg);
	// 				}
	// 			}
    //         }
    //     });
	// })

	// $('#IdUsuario_fk').select2({
	// 	// theme: 'bootstrap4',
	// 	language: 'es',
	// 	with: '100%',
	// 	// multiple: true,
	// 	maximumSelectionLength: 1,
	// 	ajax: {
	// 		url: '../../ria/rcp.php',
	// 		dataType: "json",
	// 		type: "GET",
	// 		data: function (params) {
	// 			var queryParameters = {
	// 				query: params.term,
	// 				// result_type: 'select2',
	// 				f_name: 'holis',
	// 				// add_other: '1'
	// 			}
	// 			return queryParameters;
	// 		},
	// 		processResults: function (data, params) {
	// 			data = data.map(function (item) {
	// 				return {
	// 					id: item.IdUsuario,
	// 					text: item.Nombre,
	// 					// Nombre: item.NombreCompleto,
	// 					// Nombre2: item.NombreCompleto2,
	// 					// Especialidad: item.Especialidad
	// 				}
	// 			});
	// 			return {results: data};
	// 		},
	// 	}
	// });

    /* Example */
    // function loadUsuario(id_usuario) {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "../ria/seg_usuarios_new.save.ria.php",
	// 		data: {
	// 			id_usuario: id_usuario,
	// 			op: 'load'
	// 		},
			
	// 		success: function(data){
	// 			var data = jQuery.parseJSON(data);
	// 			var result = data.result;
	// 			let url = data.ruta

	// 			if (result == 1) {
	// 				$("#id_usuario").val(data.UsuarioId);
	// 				$("#Nombres").val(data.Nombres);
	// 				$("#ApellidoPaterno").val(data.ApellidoPaterno);
	// 				$("#ApellidoMaterno").val(data.ApellidoMaterno);
	// 				$("#Usuario").val(data.Usuario);
	// 				$("#ClaveAcceso").val(data.ClaveAcceso);
	// 				$("#IpRemotaAcceso").val(data.IpRemotaAcceso);

	// 				$("#Status").val(data.Status);
	// 				$("#EsAccesoRemoto").val(data.EsAccesoRemoto);
	// 				$("#EsCualquierIp").val(data.EsCualquierIp);

	// 				$("#UsuarioPerfilId").val(data.UsuarioPerfilId);
	// 				$("#FechaClaveAcceso").val(data.FechaClaveAcceso);
	// 				$("#Caducidad").val(data.Caducidad);
	// 				$("#IdAppInicio").val(data.IdAppInicio);
	// 				$("#DepartamentoId").val(data.DepartamentoId);
	// 				$("#ClaveTel").val(data.ClaveTel);
	// 				$("#Titulo").val(data.Titulo);
	// 				$("#AccesoComedor").val(data.AccesoComedor);
	// 				$("#NumVisitas").val(data.NumVisitas);
	// 				$("#fileimg").val(data.SignFileName);

	// 				if (data.ruta == '') {
	// 					$('#delelete_img').addClass('d-none')
	// 					$("#img_show").addClass('d-none');
	// 				} else {
	// 					$('#delelete_img').removeClass('d-none')
	// 					$("#img_show").removeClass('d-none');
	// 					$("#img_show").attr('src', url);
	// 				}

	// 				if (data.Status == 1) {
	// 					$('#Status').prop('checked', true);
	// 				} else {
	// 					$('#Status').prop('checked', false);
	// 				}
	// 				if (data.EsAccesoRemoto == 1) {
	// 					$('#EsAccesoRemoto').prop('checked', true);
	// 				} else {
	// 					$('#EsAccesoRemoto').prop('checked', false);
	// 				}
	// 				if (data.EsCualquierIp == 1) {
	// 					$('#EsCualquierIp').prop('checked', true);
	// 				} else {
	// 					$('#EsCualquierIp').prop('checked', false);
	// 				}
	// 				if (data.AccesoComedor == 1) {
	// 					$('#AccesoComedor').prop('checked', true);
	// 				} else {
	// 					$('#AccesoComedor').prop('checked', false);
	// 				}
	// 			} else {
	// 				if (result == -1) {
	// 					toastr.warning(data.msg);
	// 				} else {
	// 					toastr.info(data.msg);
	// 				}
	// 			}
	// 		}
	// 	});
	// }
	
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