$(document).ready(function() {
	// mensajes especificos
	var confirmacion = 'Estas seguro de eliminar esta solicitud?'; // ADHOC
	$("#add-modal").modal('show');
	
	/* evita que el modal de abajo se cierre  */
	$(document).on('hidden.bs.modal', function (event) {
		if ($('.modal:visible').length) {
			$('body').addClass('modal-open');
		}
	});
	
	var table = $('#table-refacciones').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"searching": false,
		"ajax": {
			"url": "../../ria/agregar_refacciones.ria.php", // ADHOC
			"data": function(d) {
				d.IdCategoria_fk = $('#IdCategoria_fk').val();
				d.Descripcion = $('#Descripcion').val();
				// d.s_mostrar = $('#s_Mostrar').val();
			}
		},
		"language": {
			"url": "../../vendor/datatables/lang/Spanish.json"
		},
		"bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
		"pageLength": 5,
		"lengthMenu": [[5, 10, 20, 25, 50, 100, 200,500], [5, 10, 20, 25, 50, 100, 200, 500]],
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
			null, // 1 categoria
			null, // 2 descripcion
			null, // 3 cantidad
			null, // 4 costo
			null, // 5 sel
		]
	});

	$('#button-search').on('click', function(){
		$('#table-refacciones tbody .valores').val('');
		valores = {};
		$('#table-refacciones').DataTable().ajax.reload();
		// limpia los campos por cada busqueda 		
	});

	$('#btn-add').on( 'click', function () {
		$('#add-modal').modal('show');

	});

	$('#Ordenar').on('change', function () {
        let valor = $('#Ordenar').val();
        if (valor == 1) {
            OrderCategoria()           
        }

        if (valor == 2) {
            OrderDescripcion()
        }
    });

	function OrderCategoria() {
        table.order([3, 'asc']).draw(); // Ordenar por la primera columna (0-index) ascendente
    }

    function OrderDescripcion() {
        table.order([2, 'asc']).draw(); // Ordenar por la primera columna (0-index) ascendente
    }

	/* CIERRA EL MODAL Y LIMPIA LOS CAMPOS  */
	$("#modal-close-products").on('click', function () {
		$("#add-modal").modal('hide');
		$('#table-refacciones tbody .valores').val('');
		$('#IdCategoria_fk').val(null).trigger('change');
		$('#Descripcion').val('');
		$('#table-refacciones').DataTable().ajax.reload();
	})

	$("#btn-exit").on('click', function () {
		$("#add-modal").modal('hide');
		$('#table-refacciones tbody .valores').val('');
		$('#IdCategoria_fk').val(null).trigger('change');
		$('#Descripcion').val('');
		$('#table-refacciones').DataTable().ajax.reload();
	})

	$('#IdCategoria_fk').select2({
		// theme: 'bootstrap4',
		dropdownParent: $('#add-modal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	
	$('#IdCategoria_fk').on('change.select2', function (e) {        
		valores = {};
		$('#table-refacciones').DataTable().ajax.reload();
    });

	/* obtienen la cantida y el id del producto  */
	valores = {};
	$('#table-refacciones tbody').on('change', '.valores', function() {
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		let id = data[0]; 
		valorInput = $(this).val();
		valores[id] = valorInput;
	});	  

	/* agregar los productos seleccionados */
	$("#btn-agregar").on('click', function (event) {
		let data_json = JSON.stringify(valores);
		let IdCategoria_fk =  $('#IdCategoria_fk').val();
	
		$.ajax({
			type: "POST",
			url: "../../ria/agregar_refacciones_save.ria.php",
			data: {
				op: 'SaveData',
				data_json:  data_json,
				IdCategoria_fk: IdCategoria_fk
			},
			success: function(data){
				var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					toastr.success(data.msg);
					valores = {};
					// $('#table-refacciones').DataTable().ajax.reload();
					$("#add-modal").modal('hide');
					$('#table-refacciones tbody .valores').val('');
					$('#IdCategoria_fk').val(null).trigger('change');
					$('#Descripcion').val('');
					$('#table-solicitud').DataTable().ajax.reload();
					$('#table-refacciones').DataTable().ajax.reload();
					
				} else {
					if (result == -1) {
						valores = {};		
						toastr.warning(data.msg);														
					} else {
						valores = {};
						toastr.info(data.msg);												
					}
				}	
		  	}
		});
	});

	/* Muestra las imagenes */
	$('#table-refacciones tbody').on( 'click', '.btn-img', function () {
        var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}

		$('#modal-img').modal('show');
        let id_producto =  data[0];
		parametros = 'Id_producto=' + id_producto + '&op=ShowImg';

		$.ajax({
				type: "POST",
				url: "../../ria/agregar_refacciones_save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					if (data.img ==  '') {
						$("#show_img").attr('alt', 'Este producto no cuenta con imagen');
					} else {
						$("#show_img").attr('src', '../../images/products/'+data.img);
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
	} );
	
	$("#close-modal-img").on('click', function () {
		$("#show_img").attr('src', '');
	})

	/* CANCELA LA SOLICITUD */
	$("#cancelar").on('click', function () {
		if (!confirm(confirmacion)) return false;
		$.ajax({
				type: "POST",
				url: "../../ria/agregar_refacciones_save.ria.php",
				data: {
					op: 'Cancelar'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					$("#spinner").removeClass("d-none")
					setTimeout(Cancelar, 2000);
				} else {
					if (result == -1) {
						toastr.warning(data.msg);
					} else {
						toastr.info(data.msg);
					}
				}
			}
		});
	});

	function Cancelar() {
		window.location.href = "../views/nueva_solicitud.php";
	}

	/* muestra los productos a solicitar */
	var table_solicitud = $('#table-solicitud').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": false,
        "serverSide": false,
		"searching": false,
		"ajax": {
			"url": "../../ria/agregar_refacciones_save.ria.php", // ADHOC
			"data": function(d) {
				d.op = 'ShowProducts';
				d.IdSolicitud = $('#id_solicitud').val();
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
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 1,2,3,4 ],
				"className": 'text-center'
			}
		],
		// "order": [[3, "desc"]],
		"columns": [
			{ "data": "Id" },
			// { "data": "IdPartida" },
			{ "data": "Referencia" },
			{ "data": "NombreRefaccion" },
			{ "data": "Cantidad" },
			{ "data": "icons" }
		]
	});


	$('#table-solicitud tbody').on( 'click', '.btn-delete-product', function () {
        var data = table_solicitud.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table_solicitud.row( this ).data();
		}
		let id_partida =  $(this).attr('data-id');
		let msg_confirmacion = "Estas seguro de eliminar el producto";

		if (!confirm(msg_confirmacion)) return false;
		
		$.ajax({
				type: "POST",
				url: "../../ria/agregar_refacciones_save.ria.php",
				data: {
					id_partida: id_partida,
					op: 'Delete_product'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						$('#table-solicitud').DataTable().ajax.reload();
					} else {
						if (result == -1) {
						toastr.warning(data.msg);
					} else {
						toastr.info(data.msg);
					}
				}
			}
		})
	})


	$("#btn-revision").on('click', function () {
		$("#spinner").removeClass("d-none")
		let fecha = $("#fecha_val").val();		
		let msg_confirmacion = "Desea enviar la solicitud a aprobar a su GG?";
		if (!confirm(msg_confirmacion)) return false;

		$.ajax({
			type: "POST",
			url: "../../ria/agregar_refacciones_save.ria.php",
			data: {
				fecha: fecha,
				op: 'Revision'
			},
			success: function(data){
				var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {					
					$("#spinner").addClass("d-none")
					window.location.href = "../views/solicitudes_pendientes.php";
				} else {
					if (result == -1) {
						toastr.warning(data.msg);
					} else {
						toastr.info(data.msg);
					}
				}
			}
		})
	});

	$("#mas-informacion").on('click', function () {
		$("#informacion").toggle('d-none');
	})
} );