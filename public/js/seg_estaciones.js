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
        "searching": true,
		// "serverSide": true,
		"ajax": {
            "url":"../../ria/seg_estaciones.ria.php", // ADHOC
            "data": function(d) {
                // d.Folio = $('#folio').val();
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
				"targets": [ 1,2,3,4 ],
				"className": 'text-center'
			}
        ],
        "order": [[0, "asc"]],
		"columns": [
			null, // idcategoria 0
			null, // sel 1
			null, // categoria 2
			null, // estatus 3
			null, // IdUsuario_fk
		]
    });
 
	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        $("#IdEstacion").val(data[0])
		loadEstaciones(data[0])
	});

    function  loadEstaciones (id_estacion) {
        $.ajax({
            type: "get",
            url: "../../ria/seg_estaciones_save.ria.php",
            data: {
                IdEstacion: id_estacion,
                op: 'loadEstacion'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					$("#EstacionServicio").val(data.EstacionServicio);
                    $("#NoEstacion").val(data.NoEstacion);
					$("#EmailSupervisor").val(data.EmailSupervisor);
					$("#NombreCorto").val(data.NombreCorto);
					$("#TelSupervisor").val(data.TelSupervisor);
                    if (data.EsActivo == 1) {
                        $('#EsActivo').prop('checked', true);
                    } else {
                        $('#EsActivo').prop('checked', false);
                    }
					
                }
            }
        });
    }

    $('#btn-search').on('click', function () {
        $('#grid-table').DataTable().ajax.reload();
    })
	
	$('#button-add').on('click', function(){
		$('#DataModal').modal('show');
		
        $("#IdEstacion").val('');
        $("#EstacionServicio").val('');
        $("#NoEstacion").val('');
		$("#EmailSupervisor").val('');
		$("#NombreCorto").val('');
		$("#TelSupervisor").val('');
		$('#EsActivo').prop('checked', true);
        // clean form
		// $("#Categoria").val('');
		// $("#IdUsuario_fk").val('');
		// $('#IdUsuario_fk').val(null).trigger('change');
		
	});

	$('#IdUsuario_fk').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});


	$('#button-save').on('click', function(){
		$("#spinner").removeClass('d-none');
		var parametros = $('#form-data').serialize();
		parametros = parametros + "&op=save";
		$.ajax({
				type: "POST",
				url: "../../ria/seg_estaciones_save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						show_load();
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

	function show_load() {
		$("#spinner").addClass('d-none');		
	}

} );