$(document).ready(function() {
	// mensajes especificos
	var confirmacion = 'Desea eliminar este producto de la lista?'; // ADHOC

	$('#datetimepicker1').datetimepicker({
		format: 'L',
		locale: 'es'
	});
	
	var table = $('#grid-table').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"searching": false,
		"ajax": {
			"url": "../../ria/orden_productos.ria.php", // ADHOC
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
				"targets": [ 1,2,3,4,5 ],
				"className": 'text-center'
			}
		],
		"order": [[0, "asc"]],
		"columns": [
			null, // 0 #
			null, // categoria
			null, // 1 descripcion
			null, // 2 cantidad
			null, // 3 costo
			null, // 4 sel
		]
	});

	$('#grid-table tbody').on( 'click', '.btn-delete', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        // $("#id_categoria").val(data[0])
		productDelete(data[0])
	});

    function  productDelete (IdCotizacionDt) {
		if (!confirm(confirmacion)) return false;
        $.ajax({
            type: "POST",
            url: "../../ria/orden_productos.save.ria.php",
            data: {
                IdCotizacionDt: IdCotizacionDt,
                op: 'delete'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					toastr.success(data.msg);
					// $('#DataModal').modal('hide');
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
    }
	
	// clean form
	$('#btn-clear').on('click', function(){
		// $('#DataModal').modal('show');
		$("#IdProveedor_fk").val('');
		$("#IdArticulo_fk").val('');
		$("#Cantidad").val('');
		$("#Notas").val('');
		// $('#EsActivo').prop('checked', true);
	});

	// $('#IdUsuario_fk').select2({
	// 	// theme: 'bootstrap4',
	// 	dropdownParent: $('#DataModal'),
	// 	language: 'es',
	// 	allowClear: true,
	// 	placeholder: 'Selecciona un valor',
	// });


	$('#btn-save').on('click', function(){
		// alert("hoolis")
		var parametros = $('#form-data').serialize();
		parametros = parametros + "&op=save";
		$.ajax({
				type: "POST",
				url: "../../ria/orden_productos.save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						// $('#DataModal').modal('hide');
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
		event.preventDefault();
		
	});

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

} );