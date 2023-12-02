$(document).ready(function() {

    let confirmacion_elimina = "Estas seguro de eliminar el producto?"

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
                // d.Ordenar = $('#Ordenar').val();
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
                "targets": [ 2,3,4,5,6 ],
                "className": 'text-center'
            }
        ],
        "order": [[0, "asc"]],
        "columns": [
            null, // 0 
            null, // 1 descripcion
            null, // 2  referencia
            null, // 3  No serie
            null, // 4  Categoria
            null, // 5  imagen 
            null, // 6  sel
        ]
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



    /* select2 */
    $('#IdCategoria_fk').select2({
		theme: 'bootstrap4',
		// dropdownParent: $('#DataModal'),
		language: 'es',
		allowClear: true,
		placeholder: 'Selecciona un valor',
	});



});

