$(document).ready(function() {
	
    var table = $('#grid-table').DataTable( {
        "responsive": false,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
        "ajax": "../ria/ftp-config.ria.php", // ADHOC
        "language": {
            "url": "../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [ 0, 6 ],
                "visible": false,
                "searchable": false
            }
        ],
		"columns": [
			null, // IdEstacion
			null, // Estacion
			null, // Especifico (UsarFTPEstacion)
			null, // Tipo de servicio (FTP o SFTP)
			null, // Direccion IP
			null, // Usuario
			null, // Pass
			null, // Puerto
			null, // Tipo de funcionamiento
			null, // Ruta
			null, // Estatus (EsActivo)
			{
			data: null,
			className: "center",
			// defaultContent: '<center><button type="button" title="Editar" class="btn btn-primary btn-xs button-edit"><span class="fa fas fa-edit" aria-hidden="true"></span></button></center>'
			defaultContent: '<center><span title="Editar" class="button-edit" style="cursor: pointer;"><span class="fa fas fa-edit" aria-hidden="true"></span></span></center>'
		}]
    } );
	
	$('#passwd_confirma').keydown( function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			$('#button-save').click();
			return false;
		}
	});
	
	
	$('#button-show-edit').on('click', function(){
		ftpLoad();
	});
	
	function ftpLoad(controls) {
		var controls = (controls != undefined) ? controls : 1;
		$.ajax({
				type: "POST",
				url: "../ria/ftp-config-save.ria.php",
				data: {
					op: 'load'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						if (controls == 1) {
							$('#FTPIp').val(data.FTPIp);
							$('#FTPUser').val(data.FTPUser);
							$('#FTPPass').val(data.FTPPass);
							$('#FTPPort').val(data.FTPPort);
							$('#FTPSchedule').val(data.FTPSchedule);
							$('#FTPConnType').val(data.FTPConnType);
							$('#FTPServiceType').val(data.FTPServiceType);
							$('#FTPFolder').val(data.FTPFolder);
							connTypeChange();
						} else {
							$('#v_FTPIp').val(data.FTPIp);
							$('#v_FTPUser').val(data.FTPUser);
							$('#v_FTPPass').val(data.FTPPass);
							$('#v_FTPPort').val(data.FTPPort);
							$('#v_FTPSchedule').val(data.FTPSchedule);
							$('#v_FTPConnTypeDesc').val(data.FTPConnTypeDesc);
							$('#v_FTPServiceType').val(data.FTPServiceType);
							$('#v_FTPFolder').val(data.FTPFolder);
							if (data.FTPConnType == 1) {
								$('#v_FTPFolderContainer').hide();
							} else {
								$('#v_FTPFolderContainer').show();
							}
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
	}
	
	$('#FTPConnType').on('change', function(){
		connTypeChange();
	});
	
	function connTypeChange() {
		if ($('#FTPConnType').val() == 1) {
			$('#FTPFolder').prop('readonly', true);
			$('#FTPFolderContainer').hide();
		} else {
			$('#FTPFolder').prop('readonly', false);
			$('#FTPFolderContainer').show();
		}
	}
	
	$('#est_FTPConnType').on('change', function(){
		connTypeChangeEst();
	});

	
	function connTypeChangeEst() {
		// if ($('#est_FTPConnType').val() == 1) {
		if ($('#est_FTPConnType').find(":selected").val() == 1) {
			$('#est_FTPFolder').prop('readonly', true);
			$('#est_FTPFolderContainer').hide();
		} else {
			$('#est_FTPFolder').prop('readonly', false);
			$('#est_FTPFolderContainer').show();
		}
	}
	function usarFtpEstacionChange() {
		if ($('input[type=radio][name=UsarFTPEstacion]:checked').val() == 0) {
			$('.est_fields').prop('disabled', true);
			$('.est_fields').val('');
			$('#UsarFTPEstacionHelp').html('Se usará la configuración global');
		} else {
			$('.est_fields').prop('disabled', false);
			$('#UsarFTPEstacionHelp').html('Se usará la configuración específica de la estación');
		}
	}
	$('input[type=radio][name=UsarFTPEstacion]').change(function() {
		usarFtpEstacionChange();
	});
	
	//Timepicker
	$('.timepicker').datetimepicker({
	  format: 'HH:mm'
	});

	$('#button-save').on('click', function(){
		$.ajax({
				type: "POST",
				url: "../ria/ftp-config-save.ria.php",
				data: {
					FTPIp:	$('#FTPIp').val(),
					FTPUser: $('#FTPUser').val(),
					FTPPass: $('#FTPPass').val(),
					FTPPort: $('#FTPPort').val(),
					FTPSchedule: $('#FTPSchedule').val(),
					FTPConnType: $('#FTPConnType').val(),
					FTPServiceType: $('#FTPServiceType').val(),
					FTPFolder: $('#FTPFolder').val(),
					op: 'save'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						ftpLoad(2); // para ver si esta guardado correctamente
						$('#DataModal').modal('hide');
					} else {
						if (result == -1) {
							toastr.warning(data.msg);
						} else {
							toastr.info(data.msg);
						}
					}
				
			  }
		});
	  // event.preventDefault();
	});
	
	$('#DataModal').on('hidden.bs.modal', function (e) {
		$('#FTPIp').val('');
		$('#FTPUser').val('');
		$('#FTPPass').val('');
		$('#FTPPort').val('');
		$('#FTPSchedule').val('');
		$('#FTPConnType').val(1);
		$('#FTPServiceType').val('');
		$('#FTPFolder').val('');
		connTypeChange();
	});
	
	ftpLoad(2);
	
	// ========================= estacion =========================
	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		$('#IdEstacion').val(data[0]);
		
		ftpLoadEstacion();
	} );
	
	$('#DataModalEstacion').on('hidden.bs.modal', function (e) {
		cleanFieldsEst();
	})
	
	function ftpLoadEstacion() {
		$.ajax({
				type: "POST",
				url: "../ria/ftp-config-save.ria.php",
				data: {
					IdEstacion: $('#IdEstacion').val(),
					op: 'loadEst'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							$('#est_FTPIp').val(data.FTPIp);
							$('#est_FTPUser').val(data.FTPUser);
							$('#est_FTPPass').val(data.FTPPass);
							$('#est_FTPPort').val(data.FTPPort);
							$('#est_FTPSchedule').val(data.FTPSchedule);
							$('#est_FTPConnType').val(data.FTPConnType);
							$('#est_FTPServiceType').val(data.FTPServiceType);
							$('#es_tFTPFolder').val(data.FTPFolder);
							// $('#UsarFTPEstacion').val(data.UsarFTPEstacion);
							$('input[type=radio][name=UsarFTPEstacion]').filter('[value="' + data.UsarFTPEstacion + '"]').attr('checked', true);
							
							$('#DataModalEstacion').modal('show');
							connTypeChangeEst();
							usarFtpEstacionChange();
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
	
	$('#button-save-est').on('click', function(){
		$.ajax({
				type: "POST",
				url: "../ria/ftp-config-save.ria.php",
				data: {
					IdEstacion: $('#IdEstacion').val(),
					FTPIp: $('#est_FTPIp').val(),
					FTPUser: $('#est_FTPUser').val(),
					FTPPass: $('#est_FTPPass').val(),
					FTPPort: $('#est_FTPPort').val(),
					FTPSchedule: $('#est_FTPSchedule').val(),
					FTPConnType: $('#est_FTPConnType').val(),
					FTPServiceType: $('#est_FTPServiceType').val(),
					FTPFolder: $('#est_FTPFolder').val(),
					UsarFTPEstacion: $('input[type=radio][name=UsarFTPEstacion]:checked').val(),
					op: 'saveEst'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
						ftpLoadEstacion(); // para ver si esta guardado correctamente
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
	  // event.preventDefault();
	});
	
	function cleanFieldsEst() {
		$('#IdEstacion').val('');
		$('#UsarFTPEstacion').val(0);
		$('#est_FTPIp').val('');
		$('#est_FTPUser').val('');
		$('#est_FTPPass').val('');
		$('#est_FTPPort').val('');
		$('#est_FTPSchedule').val('');
		$('#est_FTPConnType').val(1);
		$('#est_FTPServiceType').val('');
		$('#est_FTPFolder').val('');
	}
	
} );