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
		"searching": false,
		"ajax": {
            "url":"../../ria/seg_categorias.ria.php", // ADHOC
            "data": function(d) {
                d.s_Categoria = $('#s_Categoria').val();
				d.Activo = $('#Activo').val();                
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

	$('.keydown13').keydown( function(event) {
		if (event.keyCode == 13) {
            $('#grid-table').DataTable().ajax.reload();	
		}
	});

	$("#btn-search").on('click', function () {
		$('#grid-table').DataTable().ajax.reload();	
	})
 
	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        $("#id_categoria").val(data[0])
		loadCatergoria(data[0])
	});

    function  loadCatergoria (id_categoria) {
        $.ajax({
            type: "get",
            url: "../../ria/seg_categorias_save.ria.php",
            data: {
                id_categoria: id_categoria,
                op: 'loadCategoria'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					$("#Categoria").val(data.Categoria);
                    $('#IdUsuario_fk').val(data.IdUsuario_fk).trigger('change');
                    if (data.EsActivo == 1) {
                        $('#EsActivo').prop('checked', true);
                    } else {
                        $('#EsActivo').prop('checked', false);
                    }
					
                }
            }
        });
    }
	
	$('#button-add').on('click', function(){
		$('#DataModal').modal('show');
		
		// clean form
		$("#id_categoria").val('');
		$("#Categoria").val('');
		// $("#IdUsuario_fk").val('');
		$('#IdUsuario_fk').val(null).trigger('change');
		$('#EsActivo').prop('checked', true);
	});

	$('#IdUsuario_fk').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	// $('#IdUsuario_fk').val(1).trigger('change');


	$('#button-save').on('click', function(){
		var parametros = $('#form-data').serialize();
		parametros = parametros + "&op=save";
		$.ajax({
				type: "POST",
				url: "../../ria/seg_categorias_save.ria.php",
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

	$("#btn-delete").on('click', function () {
		id_categoria =  $("#id_categoria").val();
		if (!confirm(confirmacion)) return false;
		$.ajax({
            type: "get",
            url: "../../ria/seg_categorias_save.ria.php",
            data: {
                id_categoria: id_categoria,
                op: 'delete'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					toastr.success(data.msg);
					$('#DataModal').modal('hide');
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

} );