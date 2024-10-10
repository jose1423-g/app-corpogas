$(document).ready(function() {

    let confirmacion_elimina = "Estas seguro de activar/desactivar el producto?"

    var table = $('#grid-table').DataTable( {
        "responsive": true,
        "autoWidth": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "../../ria/lista_productos.ria.php", // ADHOC
            "data": function(d) {
                d.Descripcion = $('#Descripcion').val();
                d.Referenecia = $('#Referenecia').val();
                d.IdCategoria_fk = $('#IdCategoria_fk').val();
                d.EsActivo = $('#EsActivo').val();
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
                "targets": [ 1, 2,3,4,5,6 ],
                "className": 'text-center'
            }
        ],
        "order": [[0, "asc"]],
        "columns": [
            null, // 0 
            null, // 1 icon
            null, // 2 descripcion
            null, // 3  referencia
            null, // 4  No serie
            null, // 5  Categoria
            null, // 6  imagen 
            null, // 7  sel
            // null, // 7 EsActivo
        ]
    });

    $('.keydown13').keydown( function(event) {
		if (event.keyCode == 13) {
            $('#grid-table').DataTable().ajax.reload();	
		}
	});

    $('#button-search').on('click', function(){
		$('#grid-table').DataTable().ajax.reload();
	});


    $('#Ordenar').on('change', function () {
        let valor = $('#Ordenar').val();

        if (valor == 1) {
            OrderCategoria()           
        }

        if (valor == 2) {
            OrderDescripcion()
        }

    });
    
    function OrderCategoria() {
        table.order([4, 'asc']).draw(); // Ordenar por la primera columna (0-index) ascendente
    }

    function OrderDescripcion() {
        table.order([1, 'asc']).draw(); // Ordenar por la primera columna (0-index) ascendente
    }

    /* funtion para editar */
    $('#grid-table tbody').on( 'click', '.btn-edit', function () {
		$('#DataModalEdit').modal('show');

		var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}
        $("#Id_producto").val(data[0])
		loadProdcut(data[0])
	});



    $('#grid-table tbody').on( 'click', '.btn-img', function () {
        $('#DataModal').modal('show');
        var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}

        let id_producto =  data[0];

		// if (!confirm(confirmacion_elimina)) return false;
		parametros = 'Id_producto=' + id_producto + '&op=ShowImg';

		$.ajax({
				type: "POST",
				url: "../../ria/lista_productos_save.ria.php",
				data: parametros,
				success: function(data){
					var data = jQuery.parseJSON(data);
				var result = data.result;
                if (result == 1) {
					if (data.img ==  '') {
						$("#show_img").attr('alt', 'Este producto no cuenta con imagen');
					} else {
						$("#show_img").attr('src', '../../images/products/'+data.img);
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
	  event.preventDefault();
	} );

    $("#close-modal-img").on('click', function () {
		$("#show_img").attr('src', '');
	})


    $('#grid-table tbody').on( 'click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
		if (data == undefined) {
			data = table.row( this ).data();
		}

        let id_producto =  data[0];

		if (!confirm(confirmacion_elimina)) return false;
		parametros = 'Id_producto=' + id_producto + '&op=delete';

		$.ajax({
				type: "POST",
				url: "../../ria/lista_productos_save.ria.php",
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
	} );

    function  loadProdcut (IdProducto) {
        $.ajax({
            type: "get",
            url: "../../ria/lista_productos_save.ria.php",
            data: {
                Id_producto: IdProducto,
                op:'GetProdcut'
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
				var result = data.result;
                       
                if (result == 1) {
					$("#NombreRefaccion").val(data.NombreRefaccion);
                    $("#Referencia").val(data.Referencia);
                    $("#NoSerie").val(data.NoSerie);
                    $("#img_p").text(data.img);
                    $("#img").val(data.img);
                    $("#uploadedfile").val('')                    
                    $("#IdCategoria_fkP").val(data.IdCategoria_fk);
					$('#grid-table').DataTable().ajax.reload();
                    if (data.EsActivo == 1) {
                        $('#EsActivoP').prop('checked', true);
                    } else {
                        $('#EsActivoP').prop('checked', false);
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


    $("#btn-save").on('click', function (e) { 
		$("#spinner").removeClass('d-none');
		e.preventDefault();
		
		let file =  document.getElementById('uploadedfile').files[0];	
		let es_activo =  $("#EsActivoP").is(":checked");
		
		datos = new FormData()
		
        datos.append('img', $("#img").val())
        datos.append('Id_producto', $("#Id_producto").val())
		datos.append('NombreRefaccion', $("#NombreRefaccion").val())
		datos.append('Referencia', $("#Referencia").val())
		datos.append('NoSerie', $("#NoSerie").val())
		datos.append('IdCategoria_fkP', $("#IdCategoria_fkP").val())
		if (es_activo) {
			es_activo = 1;
			datos.append('EsActivoP', es_activo)
		} else{
			es_activo = 0;
			datos.append('EsActivoP', es_activo)
		}
		datos.append('uploadedfile', file)
		datos.append('op', 'updateproduct')
        
		$.ajax({
			type: "POST",
			url: "../../ria/lista_productos_save.ria.php",
			processData: false,
			contentType: false,
			data: datos,
			success: function(data){
				var data = jQuery.parseJSON(data);
				var result = data.result;
				if (result == 1) {
					$("#spinner").addClass('d-none');
                    $('#DataModalEdit').modal('hide');
                    $("#uploadedfile").val('');		                    			
                    toastr.success(data.msg);
				} else {
					if (result == -1) {
						$("#spinner").addClass('d-none');		
						toastr.warning(data.msg);
					} else {
						$("#spinner").addClass('d-none');		
						toastr.info(data.msg);
					}
				}
			}
		});
	});

    function  show_load() {
		$("#spinner").addClass('d-none');
	}

    /* select2 */
    $('#IdCategoria_fk').select2({
		theme: 'bootstrap4',
		// dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});



});

