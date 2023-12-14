$(document).ready(function() {
	
	var table = $('#grid-table').DataTable( {
		"responsive": true,
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "../../ria/usuarios.ria.php", // ADHOC
			"data": function(d) {
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
				"visible": false,
				"searchable": false
			},
			{
				"targets": [ 1,2,4,5,6,8,13 ],
				"className": 'text-center'
			}
		],
		"order": [[0, "asc"]],
		"columns": [
			null, // 0 cargoId
			null, // 1 SubArea
			null, // 2 #paciente
			null, // 3 Nombre
			null, // 4 Genero
		]
	});

	$('#IdCategoria_fk').select2({
		theme: 'bootstrap4',
		// dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});

	// $('#IdUsuario_fk').val(1).trigger('change');
	$("#btn-clear").on('click', cleanForm);

	function cleanForm() {
		// $("#uploadedfile").val('')
		$("#NombreRefaccion").val('')
		$("#Referencia").val('')
		$("#NoSerie").val('')
		$("#uploadedfile").val('')
		// $("#IdCategoria_fk").val('');
		$('#IdCategoria_fk').val(null).trigger('change');
		$('#EsActivo').prop('checked', false);
	}

	$("#btn-save").on('click',function (e) { 
		$("#spinner").removeClass('d-none');
		e.preventDefault();
		
		let file =  document.getElementById('uploadedfile').files[0];	
		let es_activo =  $("#EsActivo").is(":checked");
		
		datos = new FormData()
		
		datos.append('NombreRefaccion', $("#NombreRefaccion").val())
		datos.append('Referencia', $("#Referencia").val())
		datos.append('NoSerie', $("#NoSerie").val())
		datos.append('IdCategoria_fk', $("#IdCategoria_fk").val())
		if (es_activo) {
			es_activo = 1;
			datos.append('EsActivo', es_activo)
		} else{
			es_activo = 0;
			datos.append('EsActivo', es_activo)
		}
		datos.append('uploadedfile', file)
		datos.append('op', 'save')
	
		$.ajax({
			type: "POST",
			url: "../../ria/seg_productos_save.ria.php",
			processData: false,
			contentType: false,
			data: datos,
			success: function(data){
				var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					show_load();				
					cleanForm()
					toastr.success(data.msg);
				} else {
					if (result == -1) {
						show_load();				
						toastr.warning(data.msg);
					} else {
						show_load();				
						toastr.info(data.msg);
					}
				}
			}
		});
	});

	function  show_load() {
		$("#spinner").addClass('d-none');		
	}

} );    