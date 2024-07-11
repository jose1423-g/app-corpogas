$(document).ready(function() {
	// mensajes especificos
	var confirmacion = '¿Estas seguro de rechazar esta solicitud?'; // ADHOC (no se deben eliminar usuarios)

	$('#datetimepicker1').datetimepicker({
		format: 'L',
		locale: 'es'
	});

	$('#datetimepicker2').datetimepicker({
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
            "url":"../../ria/reporte_solicitudes.ria.php", // ADHOC
            "data": function(d) {
                d.Folio = $('#Folio').val();
                d.s_mostrar = $('#s_mostrar').val();
				d.s_estacion = $('#s_estacion').val();
				d.s_FechaDesde = $('#s_FechaDesde').val();
				d.s_FechaHasta = $('#s_FechaHasta').val();
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
				"targets": [ 1,2,3, 4 ],
				"className": 'text-center'
			}
        ],
        "order": [[0, "asc"]],
		"columns": [
			null, // # 0	
			null, // folio 2
			null, // no estacion 3			
			null, //estacion
			null, // estatus
            null, //  fecha 5
		] 
    });

    $("#btn-search").on('click', function () {
        $('#grid-table').DataTable().ajax.reload();
    })

	$('#s_estacion').select2({
		theme: 'bootstrap4',		
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	$('#btn-excel').on('click', function() {
		let estacion = $("#s_estacion").val();
		let fecha_desde = $("#s_FechaDesde").val();
		let fecha_hasta = $("#s_FechaHasta").val();
		let estatus = $("#s_mostrar").val();		
		if(estacion){		
		var window_url = `../../reportes_excel/reporte_solicitudes_xlsx.php?op=get_exel&s_estacion=${estacion}&s_FechaDesde=${fecha_desde}&s_FechaHasta=${fecha_hasta}&s_mostrar=${estatus}`;		
		window.open(window_url, 'mywindow', 'status=1,resizable=1,height=400,width=400');
		} else {
			toastr.warning('El campo estacion es requerido');
		}
	});
	
});