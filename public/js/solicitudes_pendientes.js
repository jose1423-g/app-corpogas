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
	
} );