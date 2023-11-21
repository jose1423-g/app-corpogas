$(document).ready(function() {
	// mensajes especificos
	var confirmacion_elimina = '¿Desea eliminar este usuario?'; // ADHOC (no se deben eliminar usuarios)


    var table = $('#grid-table').DataTable( {
        "responsive": true,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
        "ajax": "../ria/usuarios.ria.php", // ADHOC
        "language": {
            "url": "../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [ 2, 3, 4, 6, 8, 10 ],
                "visible": false,
                "searchable": false
            }
        ],
		"columns": [
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			{
			data: null,
			className: "center",
			defaultContent: '<center><button type="button" title="Editar" class="btn btn-primary btn-sm button-edit"><span class="fa fas fa-edit" aria-hidden="true"></span></button><button type="button" title="Eliminar" class="btn btn-danger btn-sm button-delete"><span class="fa fas fa-trash" aria-hidden="true"></span></button></center>'
		}]
    } );
	// 

	$('#grid-table tbody').on( 'click', '.button-edit', function () {
		$('#DataModal').modal('show');
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		$("#IdUsuario").val(data[0]);
		$("#UserName").val(data[1]);
		$("#ApellidoPaterno").val(data[2]);
		$("#ApellidoMaterno").val(data[3]);
		$("#Nombre").val(data[4]);
		$("#UsuarioPerfilId").val(data[6]);
		$("#passwd").val(data[10]);
		$("#IsPasswdMod").val('0');
		if (data[8] == 1) {
			$('#EsActivo').prop('checked', true);
		} else {
			$('#EsActivo').prop('checked', false);
		}
	} );
	
	$('#button-add').on('click', function(){
		$('#DataModal').modal('show');
		
		// clean form
		$("#IdUsuario").val('');
		$("#UserName").val('');
		$("#ApellidoPaterno").val('');
		$("#ApellidoMaterno").val('');
		$("#Nombre").val('');
		$("#UsuarioPerfilId").val('');
		$("#passwd").val('');
		$("#IsPasswdMod").val('0');
		$('#EsActivo').prop('checked', true);
	});
	
	$('#button-save').on('click', function(){
		var parametros = $('#form-data').serialize();
		$.ajax({
				type: "POST",
				url: "../ria/usuarios-save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
					} else {
						if (result == -1) {
							toastr.warning(data.msg);
						} else {
							toastr.info(data.msg);
						}
					}
				
				$('#DataModal').modal('hide');
				$('#grid-table').DataTable().ajax.reload();
			  }
		});
	  event.preventDefault();
		
	});

	$('#grid-table tbody').on( 'click', '.button-delete', function () {
		if (!confirm(confirmacion_elimina)) return false;
		
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		parametros = 'IdUsuario=' + data[0] + '&op=del';
		$.ajax({
				type: "POST",
				url: "../ria/usuarios-save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
						toastr.success(data.msg);
					} else {
						if (result == -1) {
							toastr.warning(data.msg);
						} else {
							toastr.info(data.msg);
						}
					}
				
				$('#DataModal').modal('hide');
				$('#grid-table').DataTable().ajax.reload();
			  }
		});
	  event.preventDefault();
	} );
} );