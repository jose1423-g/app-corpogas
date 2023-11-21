$(document).ready(function() {
	
	$('#datetimepicker4').datetimepicker({
		format: 'L',
		locale: 'es'
	});
	$('#datetimepicker5').datetimepicker({
		format: 'L',
		locale: 'es'
	});
	$('#datetimepicker6').datetimepicker({
		format: 'L',
		locale: 'es'
	});
	$("#datetimepicker4").on("change.datetimepicker", function (e) {
		$('#datetimepicker5').datetimepicker('minDate', e.date);
	});
	$("#datetimepicker5").on("change.datetimepicker", function (e) {
		$('#datetimepicker4').datetimepicker('maxDate', e.date);
	});
	
    var table = $('#grid-table').DataTable( {
        "responsive": false,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
        "ajax": {
			"url": "../ria/archivos-lista.ria.php", // ADHOC
			"data": function(d) {
				d.s_filename = $('#s_FileName').val();
				d.s_fecha_desde = $('#s_FechaDesde').val();
				d.s_fecha_hasta = $('#s_FechaHasta').val();
				d.s_fecha_envio = $('#s_FechaEnvio').val();
				d.s_estatus_envio = $('#s_EstatusEnvio').val();
			}
		},
        "language": {
            "url": "../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [ 0, 9, 10 ],
                "visible": false,
                "searchable": false
            }
        ],

		"columns": [
			null, // IdEstacion
			null, // Estacion
			null, // FileName
			null, // Fecha
			null, // Hora
			null, // IP
			null, // Estatus Envio
			null, // Fecha Envio
			null, // Hora Envio
			null, // Mensaje
			null, // Mensaje Envio
			{
			data: null,
			className: "center",
			defaultContent: '<center><button type="button" title="Editar" class="btn btn-primary btn-xs button-edit"><span class="fa fas fa-edit" aria-hidden="true"></span></button></center>'
		}]
    } );
	
	// collapsible filter
	$('#icon_collapse_filter').on('click', function(){
		$('#collapse-show-filter').collapse('show');
		$('#collapsible-card').collapse('hide');
	});
	$('#icon_collapse_show_filter').on('click', function(){
		$('#collapse-show-filter').collapse('hide');
		$('#collapsible-card').collapse('show');
	});
	
	$('#s_FileName').keydown( function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			$('#button-search').click();
			return false;
		}
	});
	$('#button-search').on('click', function(){
		$('#grid-table').DataTable().ajax.reload();
	});
	
	$('#button-show-send').on('click', function(){
		$('#DataModal').modal('show');
	});
	$('#button-send').on('click', function(){
		$('#enviando-header').html('Enviando');
		$('#enviando-body').html('<div class="alert alert-info">Enviando...<i class="fas fa-spinner fa-spin"></i></div>');
		$('#enviando-footer').hide();
		$.ajax({
				type: "POST",
				url: "../ria/archivos-send.ria.php",
				data: {
					op: 'send'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							// $('#DataModal').modal('hide');
							$('#enviando-header').html('Proceso terminado');
							$('#enviando-body').html(data.htmlFilesAllResponses);
							$('#enviando-footer').show();
							$('#grid-table').DataTable().ajax.reload();
							toastr.success(data.msg);
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
	
	
	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		// alert(data[0]);
		
		$('#DataModalFile').modal('show');
		
		// show file data
		$('#v_IdFile').val(data[0]);
		$('#v_PL').val(data[1]);
		$('#v_FileName').val(data[2]);
		$('#v_Fecha').val(data[3] + " " + data[4]);
		$('#v_IPOrigen').val(data[5]);
		$('#v_EstatusEnvio').html(data[6]);
		$('#v_MensajeEnvio').html(data[10] + ". " + data[7] + " " + data[8]);
		var btn = "Enviar";
		if (data[11] == "1") {
			btn = "Reintentar Envio";
		} else if (data[11] == "2") {
			btn = "Reenviar";
		}
		$('#button-send-file').html(btn);
		$('#button-send-file').show();
	} );
	
	$('#button-send-file').on('click', function(){
		$('#button-send-file').html('Enviando...<i class="fas fa-spinner fa-spin"></i>');
		$.ajax({
				type: "POST",
				url: "../ria/archivos-send.ria.php",
				data: {
					IdFile: $('#v_IdFile').val(),
					op: 'sendFile'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					$('#button-send-file').hide();
					if (result == 1) {
							$('#DataModalFile').modal('hide');
							$('#grid-table').DataTable().ajax.reload();
							toastr.success(data.msg);
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
	
	$('#DataModalFile').on('hidden.bs.modal', function (e) {
		// show file data
		$('#v_IdFile').val('');
		$('#v_PL').val('');
		$('#v_FileName').val('');
		$('#v_Fecha').val('');
		$('#v_IPOrigen').val('');
		$('#v_EstatusEnvio').html('');
		$('#v_MensajeEnvio').html('');
		var btn = "Enviar";
		$('#button-send-file').html(btn);
		// $('#button-send-file').show();
	})
	
	// function ftpLoadEstacion() {
		// $.ajax({
				// type: "POST",
				// url: "../ria/ftp-config-save.ria.php",
				// data: {
					// IdEstacion: $('#IdEstacion').val(),
					// op: 'loadEst'
				// },
				// success: function(data){
					// var data = jQuery.parseJSON(data);
					// var result = data.result;
					// if (result == 1) {
							// $('#est_FTPIp').val(data.FTPIp);
							// $('#est_FTPUser').val(data.FTPUser);
							// $('#est_FTPPass').val(data.FTPPass);
							// $('#est_FTPPort').val(data.FTPPort);
							// $('#est_FTPConnType').val(data.FTPConnType);
							// $('#es_tFTPFolder').val(data.FTPFolder);
							// // $('#UsarFTPEstacion').val(data.UsarFTPEstacion);
							// $('input[type=radio][name=UsarFTPEstacion]').filter('[value="' + data.UsarFTPEstacion + '"]').attr('checked', true);
							
							// $('#DataModalEstacion').modal('show');
							// connTypeChangeEst();
							// usarFtpEstacionChange();
					// } else {
						// if (result == -1) {
							// toastr.warning(data.msg);
						// } else {
							// toastr.info(data.msg);
						// }
					// }
				
			  // }
		// });
	// }
	
	// $('#button-save-est').on('click', function(){
		// $.ajax({
				// type: "POST",
				// url: "../ria/ftp-config-save.ria.php",
				// data: {
					// IdEstacion: $('#IdEstacion').val(),
					// FTPIp: $('#est_FTPIp').val(),
					// FTPUser: $('#est_FTPUser').val(),
					// FTPPass: $('#est_FTPPass').val(),
					// FTPPort: $('#est_FTPPort').val(),
					// FTPConnType: $('#est_FTPConnType').val(),
					// FTPFolder: $('#est_FTPFolder').val(),
					// UsarFTPEstacion: $('input[type=radio][name=UsarFTPEstacion]:checked').val(),
					// op: 'saveEst'
				// },
				// success: function(data){
					// var data = jQuery.parseJSON(data);
					// var result = data.result;
					// if (result == 1) {
						// toastr.success(data.msg);
						// ftpLoadEstacion(); // para ver si esta guardado correctamente
						// $('#grid-table').DataTable().ajax.reload();
					// } else {
						// if (result == -1) {
							// toastr.warning(data.msg);
						// } else {
							// toastr.info(data.msg);
						// }
					// }
				
			  // }
		// });
	  // // event.preventDefault();
	// });
	
	// function cleanFieldsEst() {
		// $('#IdEstacion').val('');
		// $('#UsarFTPEstacion').val(0);
		// $('#est_FTPIp').val('');
		// $('#est_FTPUser').val('');
		// $('#est_FTPPass').val('');
		// $('#est_FTPPort').val('');
		// $('#est_FTPConnType').val(1);
		// $('#est_FTPFolder').val('');
	// }
	
} );