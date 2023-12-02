$(document).ready(function() {
	// mensajes especificos
	var confirmacion_elimina = '¿Desea eliminar este usuario?'; // ADHOC (no se deben eliminar usuarios)

    var table = $('#grid-table').DataTable( {
        "responsive": true,
		"autoWidth": true,
		"processing": true,
        "serverSide": true,
        "ajax": "../../ria/usuarios.ria.php", // ADHOC
        "language": {
            "url": "../../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 8,
        "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
			{
				"targets": [ 1 ],
				"className": 'text-center'
			}
        ],
		"order": [[0, "asc"]],
		"columns": [
			null, //  0
			null, //  1
			null, //  2
			null, //  3
			null, //  4
			null, //  5
			null, //  6
			null, //  7
		]
    });


	$('#grid-table tbody').on( 'click', '.btn-edit', function () {
		$('#DataModal').modal('show');
		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		let id_usuario = data[0];
		loadusuario(id_usuario)
	} );


	function loadusuario(id_usuario) {
		// alert(id_usuario);
		// $("#IdUsuario").val(data[0]);
		// $("#UserName").val(data[1]);
		// $("#ApellidoPaterno").val(data[2]);
		// $("#ApellidoMaterno").val(data[3]);
		// $("#Nombre").val(data[4]);
		// $("#UsuarioPerfilId").val(data[6]);
		// $("#passwd").val(data[10]);
		// $("#IsPasswdMod").val('0');
		// if (data[8] == 1) {
		// 	$('#EsActivo').prop('checked', true);
		// } else {
		// 	$('#EsActivo').prop('checked', false);
		// }	

		// $.ajax({
        //     type: "get",
        //     url: "../../ria/seg_categorias_save.ria.php",
        //     data: {
        //         id_categoria: id_categoria,
        //         op: 'loadCategoria'
        //     },
        //     success: function (data) {
        //         var data = jQuery.parseJSON(data);
		// 		var result = data.result;
        //         if (result == 1) {
		// 			$("#Categoria").val(data.Categoria);
        //             $('#IdUsuario_fk').val(data.IdUsuario_fk).trigger('change');
        //             if (data.EsActivo == 1) {
        //                 $('#EsActivo').prop('checked', true);
        //             } else {
        //                 $('#EsActivo').prop('checked', false);
        //             }
					
        //         }
        //     }
        // });
	}

	
	
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
				url: "../../ria/usuarios-save.ria.php",
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
				url: "../../ria/usuarios-save.ria.php",
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