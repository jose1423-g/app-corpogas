$(document).ready(function() {
	// mensajes especificos
	// var confirmacion_elimina = 'Desea eliminar este Concepto?'; // ADHOC
	// $('#datetimepicker5').datetimepicker({
	// 	format: 'L',
	// 	locale: 'es'
	// });
	
	var table = $('#grid-table').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "../../ria/usuarios.ria.php", // ADHOC
			"data": function(d) {
				// d.show_hospitalizados = $('#show_hospitalizados').val();
				// d.s_perfil = $('#s_perfil').val();
				// d.s_mostrar = $('#s_Mostrar').val();
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
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 1,2,4,5,6,8,13 ],
				"className": 'text-center'
			}
		],
		"order": [[0, "asc"]],
		"columns": [
			null, // 0 cargoId
			null, // 1 SubArea
			null, // 2 #paciente
			null, // 3 Nombre
			null, // 4 Genero
		]
	});

	// /* TABLE 2 */
	// var table_consulta = $('#grid-table-consulta').DataTable( {
	// 	"responsive": true,
	// 	"autoWidth": true,
	// 	"processing": true,
	// 	"serverSide": true,
	// 	"ajax": {
	// 		"url": "../ria/pacientes_hospitalizados_new.ria.php", // ADHOC
	// 		"data": function(d) {
	// 			d.show_consulta = $('#show_consulta').val();
	// 			// d.s_perfil = $('#s_perfil').val();
	// 			// d.s_mostrar = $('#s_Mostrar').val();
	// 		}
	// 	},
	// 	"language": {
	// 		"url": "../../vendor/plugins/datatables/lang/Spanish.json"
	// 	},
	// 	"bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
	// 	"pageLength": 14,
	// 	"lengthMenu": [[14, 20, 25, 50, 100, 200,500], [14, 20, 25, 50, 100, 200, 500]],
	// 	"columnDefs": [
	// 		{
	// 			"targets": [ 0, 11],
	// 			"visible": false,
	// 			"searchable": false
	// 		},
	// 		{
	// 			"targets": [ 1,2,4,5,6,8,13 ],
	// 			"className": 'text-center'
	// 		}
	// 	],
	// 	"order": [[0, "asc"]],
	// 	"columns": [
	// 		null, // 0 cargoId
	// 		null, // 1 SubArea
	// 		null, // 2 #paciente
	// 		null, // 3 Nombre
	// 		null, // 4 Genero
	// 		null, // 5 Edad
	// 		null, // 6 Fechaingreso
	// 		null, // 7 Estado
	// 		null, // 8 evento
	// 		null, // 9 Seguro 
	// 		null, // 10 TipoSeguro
	// 		null, // 11 Diagnostico
	// 		null, // 12 medico
	// 		null, // 13 observaciones
	// 	]
	// });
	
	// collapsible filter BUSCADOR
	/* $('#icon_collapse_filter').on('click', function(){
		$('#collapse-show-filter').collapse('show');
		$('#collapsible-card').collapse('hide');
	});
	
	$('#icon_collapse_show_filter').on('click', function(){
		$('#collapse-show-filter').collapse('hide');
		$('#collapsible-card').collapse('show');
	});
	
	$('#button-search').on('click', function(){
		$('#grid-table').DataTable().ajax.reload();
	});

	//el input funciona con el enter>
	$('.input_text').keydown( function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			$('#button-search').click();
			return false;
		}
	});
	
	$('#s_Mostrar').on('change', function(){
		$('#button-search').click();
	}); */
	
	// table hospitalizados get diagnostico
	// $('#grid-table tbody').on('click', '.btn-diagnostico', function(){
	// 	var data = table.row($(this).parents('tr')).data();
	// 	if (data == undefined) {
	// 		data = table.row( this ).data();
	// 	}
		
	// 	let cargo_id = data[8];
	// 	// alert("diagnostico "+  cargo_id)
	// 	// $('#id_extra').val(cargo_id);
	// 	// $('#CdDiagnostico').select2();
	// 	$('#modal-diagnostico').modal('show')
	// 	// loadExtra(cargo_id)
	// })

	// table hospitalizados get medico
	// $('#grid-table tbody').on('click', '.btn-medico', function(){
	// 	var data = table.row($(this).parents('tr')).data();
	// 	if (data == undefined) {
	// 		data = table.row( this ).data();
	// 	}
		
	// 	let cargo_id = data[8];
	// 	// alert("medico "+  cargo_id)
	// 	// $('#id_comentarios').val(cargo_id);
	// 	$('#modal-medico').modal('show');
	// 	// loadComentarios(cargo_id)
	// })

	//table hospitalizados get observaciones
	// $('#grid-table tbody').on('click', '.btn-observaciones', function(){
	// 	var data = table.row($(this).parents('tr')).data();
	// 	if (data == undefined) {
	// 		data = table.row( this ).data();
	// 	}
	// 	let cargo_id = data[8];
	// 	// alert("observaciones "+  cargo_id)
	// 	// $('#id_comentarios').val(cargo_id);
	// 	$('#modal-observaciones').modal('show');
	// 	// loadComentarios(cargo_id)
	// })


	/* Example select2 */
	/* $('#IdDepartamento').select2({
		// theme: 'bootstrap4',
		language: 'es',
		allowClear: true,
		placeholder: '',
		ajax: {
			url: '../ria/inv.php',
			dataType: "json",
			type: "GET",
			data: function (params) {

				var queryParameters = {
					query: params.term,
					f_name: 'cxpDepartamentosArea',
					id_area: $('#IdArea').val()
				}
				return queryParameters;
			},
			processResults: function (data) {
				return {
					results: $.map(data.data, function (item) {
						return {
							text: item.Departamento,
							id: item.IdDepartamento
						}
					})
				};
			}
		}
    }); */
	// $('#CdDiagnostico').select2();
	// var $id_area_selec2 = $('#CdDiagnostico').select2({
	// 	// theme: 'bootstrap4',
	// 	language: 'es',
	// 	allowClear: true,
	// 	placeholder: '',
	// });

	// $('#CdDiagnostico').on('focus', function () {
		// $('#CdDiagnostico').select2({
		// 	// theme: 'bootstrap4',
		// 	language: 'es',
		// 	allowClear: true,
		// 	placeholder: '',
		// 	ajax: {
		// 		url: '../ria/rcp.php',
		// 		dataType: "json",
		// 		type: "GET",
		// 		data: function (params) {

		// 			var queryParameters = {
		// 				query: params.term,
		// 				f_name: 'listaDiagnosticos',
		// 				// id_area: $('#IdArea').val()
		// 			}
		// 			return queryParameters;
		// 		},
		// 		processResults: function (data) {
		// 			return {
		// 				results: $.map(data.data, function (item) {
		// 					return {
		// 						text: item.Departamento,
		// 						id: item.IdDepartamento
		// 					}
		// 				})
		// 			};
		// 		}
		// 	}
		// });
	// })

	// LOAD  SALDOS EXTRA
	// function loadExtra(cargo_id) {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "../ria/reporte_pacientes_hospitalizados_save.ria.php",
	// 		data: {
	// 			cargo_id: cargo_id,
	// 			op: 'load-extra'
	// 		},
	// 		success: function(data){
	// 			var data = jQuery.parseJSON(data);
	// 			var result = data.result;
	// 			if (result == 1) {
	// 				$("#SaldoExtrasManual").val(data.SaldoExtrasManual);
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

	// LOAD COMENTARIOS
// 	function loadComentarios(cargo_id) {
// 		$.ajax({
// 			type: "POST",
// 			url: "../ria/reporte_pacientes_hospitalizados_save.ria.php",
// 			data: {
// 				cargo_id: cargo_id,
// 				op: 'load-comentarios'
// 			},
			
// 			success: function(data){
// 				var data = jQuery.parseJSON(data);
// 				var result = data.result;
// 				if (result == 1) {
// 					$("#ObservacionesCaja").val(data.ObservacionesCaja);
// 				} else {
// 					if (result == -1) {
// 						toastr.warning(data.msg);
// 					} else {
// 						toastr.info(data.msg);
// 					}
// 				}
// 			}
// 		});
// 	}

// 	//SAVE SALDO EXTRA
// 	$('#button-save-extra').on('click', function(event){
// 		var parametros = $('#form-extra').serialize();
// 		parametros = parametros + "&op=save-extra";
// 		$.ajax({
// 			type: "POST",
// 			url: "../ria/reporte_pacientes_hospitalizados_save.ria.php",
// 			data:parametros,
// 			success: function(data){
// 				var data = jQuery.parseJSON(data);
// 				var result = data.result;
// 				if (result == 1) {
// 					toastr.success(data.msg);
// 					$('#grid-table').DataTable().ajax.reload();
// 				} else {
// 					if (result == -1) {
// 						toastr.warning(data.msg);
// 					} else {
// 						toastr.info(data.msg);
// 					}
// 				}
// 			}
// 		});
// 		event.preventDefault();
// 	});

// 	// SAVE COMENTARIOS
// 	$('#button-save-comentarios').on('click', function(event){
// 		var parametros = $('#form-comentarios').serialize();
// 		parametros = parametros + "&op=save-comentarios";
// 		$.ajax({
// 			type: "POST",
// 			url: "../ria/reporte_pacientes_hospitalizados_save.ria.php",
// 			data:parametros,
// 			success: function(data){
// 				var data = jQuery.parseJSON(data);
// 				var result = data.result;
// 				if (result == 1) {
// 					toastr.success(data.msg);
// 					$('#grid-table').DataTable().ajax.reload();
// 				} else {
// 					if (result == -1) {
// 						toastr.warning(data.msg);
// 					} else {
// 						toastr.info(data.msg);
// 					}
// 				}
// 			}
// 		});
// 		event.preventDefault();
// 	});

// 	// EXPOTAR A EXCEL
// 	$('#btn-exportar').on('click', function(){
// 		var window_url = "reporte_pacientes_hospitalizados_export_xls.php?";
// 		window.open(window_url, 'mywindow', 'status=1,resizable=1,height=400,width=400');
// 	});	
} );    