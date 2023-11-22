$(document).ready(function() {
    var table = $('#grid-table').DataTable( {
        "responsive": true,
		"autoWidth": true,
		"processing": true,
        // "serverSide": true,
		"searching": true,
        "ajax": {
			"url": "../../ria/seg_perfilesaplicaciones.ria.php",
			"data": function(d) {
			}
		},
        "language": {
            "url": "../../vendor/datatables/lang/Spanish.json"
        },
        "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
        "pageLength": 15,
        "lengthMenu": [[15, 25, 50, 100, 200, 500], [15, 25, 50, 100, 200, 500]],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
			{
                "targets": [],
                "visible": false,
                "searchable": false
			}
        ],
		"order": [[ 2, "asc" ]],
		"columns": [
			null, // id
			null, // sel
			null, // file name 
		]	
    });
	
	
	$('#grid-table tbody').on('click', '.button-edit', function(){

		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
			$('#content-table').removeClass('d-none');
			perfil = data[2];
			loadPerfil(data[0], perfil);
	});

	$('#table-clientes tbody').on('click', '.idapp', function(){

		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
		
		var id_app = $(this).attr('data-id');
		
		var check_app = ($(this).prop('checked') ) ? 1 : 0;
		
		if(check_app){
			$.ajax({
				type: "POST",
				url: "../../ria/seg_perfilesaplicaciones_save.ria.php",
				data:{
					id_perfil: $("#id_perfil").val(),
					id_app: id_app,
					check_app: check_app,
					op:'savePerfil'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							toastr.success(data.msg);
							$('#table-clientes').DataTable().ajax.reload();
						} else {
							toastr.warning(data.msg);
						}
				}
			});
		} else {
			$.ajax({
				type: "POST",
				url: "../../ria/seg_perfilesaplicaciones_save.ria.php",
				data:{
					id_perfil: $("#id_perfil").val(),
					id_app: id_app,
					check_app: check_app,
					op:'savePerfil'
				},
				success: function(data){
					var data = jQuery.parseJSON(data);
					var result = data.result;
					if (result == 1) {
							toastr.success(data.msg);
							$('#table-clientes').DataTable().ajax.reload();
						} else {
							toastr.warning(data.msg);
						}
				}
			});
		}			
	});

	$("#button-show-all").on('click', function () {
		$("#id_perfil_all").val(1)
		$("#button-show-sel").removeClass('active')
		$("#button-show-all").addClass('active')
		$('#table-clientes').DataTable().ajax.reload();

	})

	$("#button-show-sel").on('click', function () {
		$("#button-show-all").removeClass('active')
		$("#button-show-sel").addClass('active')
		$("#id_perfil_all").val(0)
		$('#table-clientes').DataTable().ajax.reload();
	})

	$("#btn-close").on('click', function () {
		$('#content-table').addClass('d-none');
	});


	function loadPerfil(id_perfil, NombrePerfil) {
		$("#id_perfil").val(id_perfil);
		$("#op").val();
		$("#NombrePerfil").text(NombrePerfil);
		$('#table-clientes').DataTable().ajax.reload();
	}

	
	let table_clientes = $('#table-clientes').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
        // "serverSide": true,
		"searching": true,
		"ajax": {
			"url": "../../ria/seg_perfilesaplicaciones_save.ria.php",
			"data": function(d) {
				d.id_perfil = $('#id_perfil').val();
				d.s_is_show_all = $('#id_perfil_all').val();
				d.op = $('#op').val();
	
			}
		},
		"language": {
			"url": "../../vendor/datatables/lang/Spanish.json"
		},
		"bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
		"pageLength": 10,
		"lengthMenu": [[10, 15, 20, 25, 30, 100, 200, 300, 500], [10, 15, 20, 25, 30, 100, 200, 300, 500]],
		"columnDefs": [
			{
				"targets": [ 0 ],
				"visible": false,
				"searchable": false,
			},
			{
				"targets": [ 1 ],
				"className": 'text-center'
			}
		],
		"columns": [
			{ "data": "IdApp" },
			{ "data": "Sel" },
			{ "data": "FileName",
				"className": 'text-center'
			},
			{ "data": "Descripcion" }
		],
		"order": [[ 2, "asc" ]]
	});	

	$('#table-clientes').DataTable().ajax.reload();

});

