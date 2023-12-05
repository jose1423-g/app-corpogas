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
            "url":"../../ria/perfiles.ria.php", // ADHOC
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
				"targets": [ 0, 1, 2, 3 ],
				"className": 'text-center'
			}
        ],
        "order": [[0, "asc"]],
		"columns": [
			null, // idcategoria 0
			null, // sel 1
			null, // categoria 2
			null, // estatus 3
			// null, // IdUsuario_fk
		]
    });
 
	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        $("#UsuarioPerfilId").val(data[0])
		loadPerfiles(data[0])
	});

    function  loadPerfiles (id_perfil) {
        $.ajax({
            type: "POST",
            url: "../../ria/perfiles_save.ria.php",
            data: {
                UsuarioPerfilId: id_perfil,
                op: 'loadPerfiles'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					$("#NombrePerfil").val(data.NombrePerfil);
                    // $("#NoEstacion").val(data.NoEstacion);
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
		
        $("#UsuarioPerfilId").val('');
        $("#NombrePerfil").val('');
		$('#EsActivo').prop('checked', true);
        
		
	});

	$('#button-save').on('click', function(){
		var parametros = $('#form-data').serialize();
		parametros = parametros + "&op=save";
		$.ajax({
				type: "POST",
				url: "../../ria/perfiles_save.ria.php",
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
    //         url: "../../ria/seg_estaciones_save.ria.php",
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