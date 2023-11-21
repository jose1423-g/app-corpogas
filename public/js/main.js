$(document).ready(function () {

    $("#btn-menu").on('click', function () {
        let classmenu = $("#content-menu").hasClass('menu');
        if (classmenu) {
            $(".menu-item a > p").removeClass('d-none');
            $(".menu-item a").removeClass('justify-content-center');
            $("#content-menu").css({'width': '22.5rem',});
            $("#content-menu").removeClass('menu');
        } else {
            $(".menu-item a > p").addClass('d-none');
            $(".menu-item a").addClass('justify-content-center');
            $("#content-menu").css({'width': '4.5rem',});
            $("#content-menu").addClass('menu');
        }
    });



    // $(document).ready(function() {
    //     // mensajes especificos
    //     var confirmacion_elimina = 'Desea eliminar el usuario?'; // ADHOC
    
        
    //     $('#datetimepicker5').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    
    //     $('#datetimepicker6').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
        
    //     $('#datetimepicker7').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    
    //     $('#datetimepicker8').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    
    //     var table = $('#grid-table').DataTable( {
    //         "responsive": true,
    //         "autoWidth": true,
    //         "processing": true,
    //         "serverSide": true,
    //         "ajax": {
    //             "url": "../ria/pacientes_cirugias_new.ria.php", // ADHOC
    //             "data": function(d) {
    //                 d.s_nombres = $('#s_nombres').val();
    //                 d.s_FechaDesde = $("#s_FechaDesde").val()
    //                 d.s_FechaHasta = $("#s_FechaHasta").val()
    //                 d.s_medico = $("#s_medico").val();
    //                 d.s_quirofano = $("#s_quirofano").val();
    //                 d.s_cap_urgencias = $("#s_cap_urgencias").val();
    //                 d.s_canceladas =  $('#s_canceladas').is(':checked')
    //                 d.s_Mostrar = $('#s_Mostrar').val();
    //             }
    //         },
    //         "language": {
    //             "url": "../vendor/plugins/datatables/lang/Spanish.json"
    //         },
    //         "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
    //         "pageLength": 10,
    //         "lengthMenu": [[10, 15, 20, 25, 50, 100, 200,500], [10, 15, 20, 25, 50, 100, 200, 500]],
    //         "columnDefs": [
    //             {
    //                 "targets": [ 0 ],
    //                 "visible": false,
    //                 "searchable": false
    //             },
    //             {
    //                 "targets": [ 1 ],
    //                 "className": 'text-center'
    //             }
    //         ],
    //         //"order": [[2, "asc"]],
    //         "columns": [
    //             null, // Id
    //             null, // sel
    //             null, // Nombre
    //             null, // fecha
    //             null, // duracion
    //             null, // fecha final
    //             null, // tipo prog ciru
    //             null, // procedimiento
    //             null, // captura .urg
    //             null, // Nombre medico
    //             null, // quirofano
    //             null, // capturado por
    //             null, // realizado
    //             null, // estatus
    //             null, // observaciones
    //         ]
    //     });
        
    //     // collapsible filter
    //     $('#icon_collapse_filter').on('click', function(){
    //         $('#collapse-show-filter').collapse('show');
    //         $('#collapsible-card').collapse('hide');
    //     });
    //     $('#icon_collapse_show_filter').on('click', function(){
    //         $('#collapse-show-filter').collapse('hide');
    //         $('#collapsible-card').collapse('show');
    //     });
        
    //     $('#button-search').on('click', function(){
    //         $('#grid-table').DataTable().ajax.reload();
    //     });
        
    //     $('.input_text').keydown( function(event) {
    //         if (event.keyCode == 13) {
    //             event.preventDefault();
    //             $('#button-search').click();
    //             return false;
    //         }
    //     });
        
    //     $('#s_Mostrar').on('change', function(){
    //         $('#button-search').click();
    //     });
        
    //     $('#grid-table tbody').on( 'click', '.button-edit', function () {
    //         $('#DataModal').modal('show');
            
    //         var data = table.row($(this).parents('tr')).data();
    //         if (data == undefined) {
    //             data = table.row( this ).data();
    //         }
    //         $("#id_cirugia").val(data[0]);
            
    //         loadCirugia(data[0]);
    //     } );
    
    //     function loadCirugia(id_cirugia) {
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/pacientes_cirugias_new_save.ria.php",
    //             data: {
    //                 id_cirugia: id_cirugia,
    //                 op: 'load'
    //             },
                
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     $("#id_cirugia").val(data.IdCirugia);
    //                     $("#ApellidoPaterno").val(data.ApellidoPaterno);
    //                     $("#ApellidoMaterno").val(data.ApellidoMaterno);
    //                     $("#NombrePaciente").val(data.NombrePaciente);
    //                     $("#FechaNacimiento").val(data.FechaNacimiento); 
    //                     // $("#showFechaNacimiento").val(data.FechaNacimiento);
    //                     $("#Sexo").val(data.Sexo);
    //                     $("#FechaInicio").val(data.FechaInicio);
    //                     $("#HoraInicio").val(data.Hora);
    //                     $("#MinInicio").val(data.Min);
    //                     $("#AmInicio").val(data.AmInicio);
    //                     // $("#Duracion").val(data.Duracion);
    //                     $("#DuracionHora").val(data.DuracionHora);
    //                     $("#DuracionMin").val(data.DuracionMin);
    //                     // $("#FechaInicioReal").val(data.FechaInicioReal);
    //                     $("#FechaInicioRealHora").val(data.FechaInicioRealHora);
    //                     $("#FechaInicioRealMin").val(data.FechaInicioRealMin);
    //                     $("#FechaInicioRealAm").val(data.FechaInicioRealAm);
    
    //                     $("#IdQuirofano").val(data.IdQuirofano);
    //                     // $("#FechaFinalReal").val(data.FechaFinalReal);
    //                     $("#FechaFinalRealHora").val(data.FechaFinalRealHora);
    //                     $("#FechaFinalRealMin").val(data.FechaFinalRealMin);
    //                     $("#FechaFinalRealAm").val(data.FechaFinalRealAm);
    
    //                     $("#TipoCirugia").val(data.TipoCirugia);
    //                     $("#CirugiaAbiertaCerrada").val(data.CirugiaAbiertaCerrada);
    //                     $("#EsCaptadaDesdeUrgencias").val(data.EsCaptadaDesdeUrgencias);
    //                     $("#TipoEvento").val(data.TipoEvento);
    //                     $("#IdTipoEstancia").val(data.IdTipoEstancia);
    //                     $("#NombreMedico").val(data.NombreMedico);
    //                     $("#Especialidad").val(data.Especialidad);
    //                     $("#NombreMedicoAntestesia").val(data.Anestesiologo);
    //                     $("#Anestesia").val(data.Anestesia);
    //                     $("#NombreAuxiliar").val(data.NombreAuxiliar);
    //                     $("#NombreInstrumentista").val(data.NombreInstrumentista);
    //                     $("#NombrePrimerAyudante").val(data.NombrePrimerAyudante);
    //                     $("#Observaciones").val(data.Observaciones);
    //                     $("#EsRealizada").val(data.EsRealizada);
    
    //                     $("#CanceladoPor").text(data.CanceladoPor);
    //                     $("#FechaCancelacion").text(data.FechaCancelacion);
    //                     $("#MotivoCancelaciontext").text(data.MotivoCancelacion);
    //                     $("#MotivoCancelacion").val(data.MotivoCancelacion);
    
    
    //                     if (data.Estatus == 3) {
    //                         $("#button-save").attr('disabled', 'disabled');
    //                         $("#btn-cancel").attr('disabled', 'disabled');
    //                         $("#info_cancel").removeClass('d-none')	
                    
    //                     }
    
    //                     if (data.NombrePaciente != '') {
    //                         $("#DataName").removeClass('d-none');	
    //                     }
                        
    //                     if (data.NombreMedico != '') {
    //                         $("#NameMedico").removeClass('d-none');
    //                         $("#ShowEspecialidad").removeClass('d-none')
    //                     } 
                        
    //                     if (data.Anestesiologo != '') {
    //                         $("#DataNombreMedicoAnestecia").removeClass('d-none')
    //                     }
                        
    //                     if (data.EsRealizada == 1) {
    //                         $('#EsRealizada').prop('checked', true);
    //                     } else {
    //                         $('#EsRealizada').prop('checked', false);
    //                     }
    //                     if (data.EsCaptadaDesdeUrgencias == 1) {
    //                         $('#EsCaptadaDesdeUrgencias').prop('checked', true);
    //                     } else {
    //                         $('#EsCaptadaDesdeUrgencias').prop('checked', false);
    //                     }
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }
    //             }
    //         });
    //     }
        
    //     $('#button-add').on('click', function(){
    //         cleanFields();
    //         $("#DataName").addClass('d-none');
    //         $("#NameMedico").addClass('d-none');
    //         $("#ShowEspecialidad").addClass('d-none')
    //         $('#DataModal').modal('show');
    //         $("#info_cancel").addClass('d-none')
    //     });
        
    //     function cleanFields() {
    //         $("#id_cirugia").val("");
    //         $("#ApellidoPaterno").val("");
    //         $("#ApellidoMaterno").val("");
    //         $("#NombrePaciente").val("");
    //         $("#FechaNacimiento").val(""); 
    //         $("#Sexo").val("");
    //         $("#FechaInicio").val("");
    //         $("#HoraInicio").val("01");
    //         $("#MinInicio").val("00");
    //         $("#AmInicio").val("am");
    //         $("#DuracionHora").val("01");
    //         $("#DuracionMin").val("00");
    //         $("#FechaInicioRealHora").val("");
    //         $("#FechaInicioRealMin").val("");
    //         $("#FechaInicioRealAm").val("");
    //         $("#IdQuirofano").val("");
    //         $("#FechaFinalRealHora").val("");
    //         $("#FechaFinalRealMin").val("");
    //         $("#FechaFinalRealAm").val("");
    //         $("#TipoCirugia").val("");
    //         $("#CirugiaAbiertaCerrada").val(1);
    //         $("#EsCaptadaDesdeUrgencias").val("");
    //         $("#TipoEvento").val("");
    //         $("#IdTipoEstancia").val("");
    //         $("#NombreMedico").val("");
    //         $("#Especialidad").val("");
    //         $("#Anestesiologo").val("");
    //         $("#Anestesia").val("");
    //         $("#NombreAuxiliar").val("");
    //         $("#NombreInstrumentista").val("");
    //         $("#NombrePrimerAyudante").val("");
    //         $("#Observaciones").val("");
    //         $("#EsRealizada").val("");
    //         $("#button-save").removeAttr('disabled');
    //         $("#btn-cancel").removeAttr('disabled');
    //         $("#CanceladoPor").text("");
    //         $("#FechaCancelacion").text("");
    //         $("#MotivoCancelaciontext").text("");
    //         // $("#EsVerNotasOtros").prop('checked', false);
    //     }
    
    //     /* select2 pacientes*/
    //     $('#IdPaciente').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         with: '100%',
    //         multiple: true,
    //         maximumSelectionLength: 1,
    //         ajax: {
    //             url: '../ria/rcp.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: function (params) {
    
    //                 var queryParameters = {
    //                     query: params.term,
    //                     result_type: 'select2',
    //                     f_name: 'listaPacientes',
    //                     add_other: '1'
    //                 }
    //                 return queryParameters;
    //             },
    //             processResults: function (data, params) {
    //                 data = data.map(function (item) {
    //                     return {
    //                         id: item.id,
    //                         text: item.Paciente,
    //                         Nombre: item.Nombre,
    //                         Paterno: item.Apellidos,
    //                         Materno: item.ApellidosM,
    //                         Sexo: item.sexo,
    //                         Nacimiento: item.cumpleanios,
    //                     }
    //                 });
    //                 return {results: data};
    //             },
    //         }
    //     });
        
    //     $("#IdPaciente").on('change', function () {
    //         var data = $(this).select2('data')
    //         var id = $("#IdPaciente").val();
    //         if (data && data.length > 0) {
    //             if (id == -1) { //si el valor del select2 es -1 el medico se pone a mano 
    //                 $("#DataName").removeClass('d-none')
    //                 $("#DataDate").addClass("d-none")
    //                 $("#FechaNacimiento").val("")
    //                 $("#ReadFechaNacimiento").text("")
    //                 $("#NombrePaciente").val("")
    //                 $("#ApellidoPaterno").val("")
    //                 $("#ApellidoMaterno").val("")
    //                 $("#Sexo").val("")
    //             } else { // de lo contrario selecciona los valores  y los imprime y inserta en sus inputs correspondientes
    //                 $("#DataDate").removeClass("d-none");
    //                 $("#DataName").removeClass('d-none')
    //                 $("#NombrePaciente").val(data[0].Nombre)
    //                 $("#ApellidoPaterno").val(data[0].Paterno)
    //                 $("#ApellidoMaterno").val(data[0].Materno)
    //                 $("#Sexo").val(data[0].Sexo)
    //                 /* funtion show  */
    //                 fechaShow(data[0].Nacimiento);
    //             }
    //         } else {
                
    //         }
    //     });
    
    //     function fechaShow(fecha) {
    //         $.ajax({
    //             url: '../ria/rcp.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: {
    //                 fecha_nacimiento: fecha,
    //                 f_name: 'fechaNacimientoShow'
    //             },
    //             success: function (data) {
    //                 fecha = data['fecha_nacimiento']
    //                 $("#FechaNacimiento").val(fecha)
    //                 $("#ReadFechaNacimiento").text(fecha)
    //             }, 
    //             error: function (jqXHR, estado, error) {
    //                 console.log(estado)
    //                 console.log(error)
    //             }
    //         });
    //     }
    
    //     /* select2 Medicos*/
    //     $('#IdMedico').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         with: '100%',
    //         multiple: true,
    //         maximumSelectionLength: 1,
    //         ajax: {
    //             url: '../ria/rcp.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: function (params) {
    
    //                 var queryParameters = {
    //                     query: params.term,
    //                     result_type: 'select2',
    //                     f_name: 'doctorsList',
    //                     add_other: '1'
    //                 }
    //                 return queryParameters;
    //             },
    //             processResults: function (data, params) {
    //                 data = data.map(function (item) {
    //                     return {
    //                         id: item.idMedico,
    //                         text: item.NombreCompleto,
    //                         Nombre: item.NombreCompleto,
    //                         Nombre2: item.NombreCompleto2,
    //                         Especialidad: item.Especialidad
    //                     }
    //                 });
    //                 return {results: data};
    //             },
    //         }
    //     });
        
    //     $("#IdMedico").on('change', function () {
    //         var data = $(this).select2('data')
    //         var id = $("#IdMedico").val();
    //         if (data && data.length > 0) {
    //             if (id == -1) { //si el valor del select2 es -1 el medico se pone a mano 
    //                 $("#ShowEspecialidad").removeClass('d-none')
    //                 $("#NameMedico").removeClass('d-none');
    //                 $("#NombreMedico").val("");
    //                 $("#Especialidad").val("");
    //             } else { // de lo contrario selecciona los valores  y los imprime y inserta en sus inputs correspondientes
    //                 $("#DataEspecialidad").removeClass("d-none");
    //                 $("#ReadEpecialidad").text(data[0].Especialidad);
    //                 $("#NombreMedico").val(data[0].Nombre2);
    //                 $("#Especialidad").val(data[0].Especialidad);
    
    //             }
    //         } else {
    //             $("#DataEspecialidad").addClass('d-none');
    //             $("#ShowEspecialidad").addClass('d-none')
    //             $("#NameMedico").addClass('d-none');
    //         }
    //     });
    
    //     /* select2 Anestesiologo*/
    //     $('#IdMedicoAnestesia').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         with: '100%',
    //         multiple: true,
    //         maximumSelectionLength: 1,
    //         ajax: {
    //             url: '../ria/rcp.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: function (params) {
    
    //                 var queryParameters = {
    //                     query: params.term,
    //                     result_type: 'select2',
    //                     f_name: 'doctorsList',
    //                     add_other: '1'
    //                 }
    //                 return queryParameters;
    //             },
    //             processResults: function (data, params) {
    //                 data = data.map(function (item) {
    //                     return {
    //                         id: item.idMedico,
    //                         text: item.NombreCompleto,
    //                         Nombre2: item.NombreCompleto2,
    //                     }
    //                 });			
    //                 return {results: data};
    //             },
    //         }
    //     });
        
    //     $("#IdMedicoAnestesia").on('change', function () {
    //         var data = $(this).select2('data')
    //         var id = $("#IdMedicoAnestesia").val();
    //         if (data && data.length > 0) {
    //             if (id == -1) { //si el valor del select2 es -1 el medico se pone a mano 
    //                 $("#DataNombreMedicoAnestecia").removeClass('d-none')
    //                 $("#NombreMedicoAntestesia").val("");
    //             } else { // de lo contrario selecciona los valores  y los imprime y inserta en sus inputs correspondientes
    //                 $("#NombreMedicoAntestesia").val(data[0].Nombre2);
    //             }
    //         } else {
    //             $("#DataNombreMedicoAnestecia").addClass('d-none');
    //         }
    //     });
    
    //     //save form
    //     $('#button-save').on('click', function(event){
    //         var parametros = $('#form-data').serialize();
    //         parametros = parametros + "&op=save";
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/pacientes_cirugias_new_save.ria.php",
    //             data:parametros,
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     toastr.success(data.msg);
    //                     $("#id_cirugia").val(data.id_cirugia)
    //                     loadCirugia($("#id_cirugia").val());
    //                     $('#grid-table').DataTable().ajax.reload();
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }
    //             }
    //         });
    //         event.preventDefault();
    //     });
    
    //     //evita que el scroll desaparezca cuando se abre un segundo modal
    //     $(document).on('hidden.bs.modal', function (event) {
    //         if ($('.modal:visible').length) {
    //             $('body').addClass('modal-open');
    //         }
    //     });
    
    //     $("#btn-cancelar").on('click', function () {
    //         id_cirugia = $("#id_cirugia").val();
    //         $("#id_cirugia_cancel").val(id_cirugia);
    //         $("#modal_cancel").modal("show");
    //     })
    
    //     $('#btn-cancel').on('click', function () {
    //         // if (!confirm(confirmacion_elimina)) return false;
    //         var parametros = $('#form-data-cancel').serialize();
    //         parametros = parametros + "&op=cancel";
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/pacientes_cirugias_new_save.ria.php",
    //             data: parametros,
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     toastr.success(data.msg);
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }			
    //                 // $('#DataModal').modal('hide');
    //                 $('#grid-table').DataTable().ajax.reload();
    //             }
                
    //         });
    //     });
    
    //     $('#DataModal').on('hidden.bs.modal', function (e) {
    //         cleanFields();	
    //     })
    
    //     //Expotar
    //     $("#btn-exportar").on('click', function () {
            
    //         FechaDesde = $("#s_FechaDesde").val()
    //         FechaHasta = $("#s_FechaHasta").val()
    //         nombres = $('#s_nombres').val();
    //         medico = $("#s_medico").val();
    //         quirofano = $("#s_quirofano").val();
    //         Mostrar = $('#s_Mostrar').val();
    //         canceladas =  $('#s_canceladas').is(':checked')
    //         cap_urgencias = $("#s_cap_urgencias").val();
    
    //         var link = "pacientes_cirugias_exporta_xls.php?"+"s_FechaInicio="+FechaDesde+"&s_FechaInicioH="+FechaHasta+"&s_NombrePaciente="+nombres+"&s_NombreMedico="+medico+"&s_IdQuirofano="+quirofano+"&s_Estatus="+Mostrar+"&s_MostrarCanceladas="+canceladas+"&s_EsCaptadaDesdeUrgencias="+cap_urgencias;
    
    //         window.location.href = link;
    //     })
    
        
    // } );


    // $(document).ready(function() {
    //     // mensajes especificos
    //     var confirmacion_elimina = 'Desea eliminar este colaborador?'; // ADHOC
    
    //     $('#datetimepicker1').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    //     $('#datetimepicker2').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    //     $('#datetimepicker3').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    //     $('#datetimepicker4').datetimepicker({
    //         format: 'L',
    //         locale: 'es'
    //     });
    //     var table = $('#grid-table').DataTable( {
    //         "responsive": true,
    //         "autoWidth": true,
    //         "processing": true,
    //         "serverSide": true,
    //         "ajax": {
    //             "url": "../ria/nom_empleados.ria.php", // ADHOC
    //             "data": function(d) {
    //                 d.s_razon_social = $('#s_RazonSocial').val();
    //                 d.s_estatus = $('#s_Estatus').val();
    //                 d.s_id_area = $('#s_IdArea').val();
    //                 d.s_id_departamento = $('#s_IdDepartamento').val();
    //             }
    //         },
    //         "language": {
    //             "url": "../vendor/plugins/datatables/lang/Spanish.json"
    //         },
    //         "bInfo" : true, // Mostrando registros del 1 al 10 de un total de 
    //         "pageLength": 8,
    //         "lengthMenu": [[8, 10, 25, 50, 100, 200, 500], [8, 10, 25, 50, 100, 200, 500]],
    //         "columnDefs": [
    //             {
    //                 "targets": [ 0 ],
    //                 "visible": false,
    //                 "searchable": false
    //             }
    //         ],
    //         "columns": [
    //             null, // IdEmpleado
    //             {
    //                 data: null,
    //                 className: "center",
    //                 defaultContent: '<center><button type="button" title="Editar" class="btn btn-primary btn-xs button-edit"><span class="fa fas fa-edit" aria-hidden="true"></span></button><button type="button" title="Eliminar" class="btn btn-danger btn-xs button-delete"><span class="fa fas fa-trash" aria-hidden="true"></span></button></center>'
    //             },
    //             null, // empresa
    //             null, // nombre
    //             null, // RFC 
    //             null, // Curp
    //             null, // Gerencia/Area
    //             null, // Departamento
    //             null // Estatus
    //         ]
    //     });
        
    //     $('#button-search').on('click', function(){
    //         $('#grid-table').DataTable().ajax.reload();
    //     });
    
    //     $('#grid-table tbody').on( 'click', '.button-edit', function () {
    //         $('#DataModal').modal('show');
    //         var data = table.row($(this).parents('tr')).data();
    //         if (data == undefined) {
    //             data = table.row( this ).data();
    //         }
    //         $(".IdEmpleado").val(data[0]);
            
    //         loadEmpleado(data[0]);
    //     } );
        
    //     $('#s_RazonSocial').keydown( function(event) {
    //         if (event.keyCode == 13) {
    //             event.preventDefault();
    //             $('#button-search').click();
    //             return false;
    //         }
    //     });
    
    //     function loadEmpleado(id_empleado) {
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/nom_empleados_save.ria.php",
    //             data: {
    //                 IdEmpleado: id_empleado,
    //                 op: 'load'
    //             },
                
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     $("#Foto").attr('src', data.Foto +  '?' + Date.now());
    //                     $("#ApellidoPaterno").val(data.ApellidoPaterno);
    //                     $("#ApellidoMaterno").val(data.ApellidoMaterno);
    //                     $("#Nombre").val(data.Nombre);
    //                     $("#Sexo").val(data.Sexo);
    //                     $("#CdCatorcena").val(data.CdCatorcena);
    //                     $("#CdEmpleado").val(data.CdEmpleado);
    //                     $("#FechaNacimiento").val(data.FechaNacimiento);
    //                     $("#FechaIngreso").val(data.FechaIngreso);
    //                     $("#LugarNacimiento").val(data.LugarNacimiento);
    //                     $("#Rfc").val(data.Rfc);
    //                     $("#Curp").val(data.Curp);
    //                     $("#EstadoCivil").val(data.EstadoCivil);
    //                     $("#Domicilio").val(data.Domicilio);
    //                     $("#Escolaridad").val(data.Escolaridad);
    //                     $("#CodigoPostal").val(data.CodigoPostal);
    //                     $("#NumSeguridadSocial").val(data.NumSeguridadSocial);
    //                     $("#NumeroCedula").val(data.NumeroCedula);
    //                     $("#Ciudad").val(data.Ciudad);
    //                     $("#ExpedienteDigital").val(data.ExpedienteDigital);
    //                     $("#Contratos").val(data.Contratos);
    //                     $("#EmpresaId").val(data.EmpresaId);
    //                     $("#Tipo").val(data.Tipo);
    //                     $("#Email").val(data.Email);
    //                     $("#IdConcepto").val(data.IdConcepto);
    //                     $("#Estatus").val(data.Estatus);
    //                     $("#IdArea").val(data.IdArea);
    //                     $("#IdDepartamento").val(data.IdDepartamento);
    //                     $("#Puesto").val(data.Puesto);
    //                     $("#Horario").val(data.Horario);
    //                     $("#SueldoMensual").val(data.SueldoMensual);
    //                     $("#Bono").val(data.Bono);
    //                     $("#SueldoImss").val(data.SueldoImss);
    //                     $("#SalarioDiarioIntegrado").val(data.SalarioDiarioIntegrado);
    //                     $("#IdBanco").val(data.IdBanco);
    //                     $("#NumTarjeta").val(data.NumTarjeta);
    //                     $("#NumCuenta").val(data.NumCuenta);
    //                     $("#NumTarjetaVales").val(data.NumTarjetaVales);
    //                     $("#ClaveInterBancaria").val(data.ClaveInterBancaria);
    //                     $("#InfonavitNumero").val(data.InfonavitNumero);
    //                     $("#InfonavitFactorDesc").val(data.InfonavitFactorDesc);
    //                     $("#InfonavitImporteCred").val(data.InfonavitImporteCred);
    //                     $("#FonacotNumero").val(data.FonacotNumero);
    //                     $("#FonacotFactorDesc").val(data.FonacotFactorDesc);
    //                     $("#FonacotImporteCred").val(data.FonacotImporteCred);
    //                     $("#Beneficiario").val(data.Beneficiario);
    //                     $("#BeneficiarioRfc").val(data.BeneficiarioRfc);
    //                     $("#BeneficiarioParentesco").val(data.BeneficiarioParentesco);
    //                     $("#Antiguedad").val(data.Antiguedad);
    //                     $("#NumEmpleado").val(data.NumEmpleado);
    //                     $("#ClaveEntFed").val(data.ClaveEntFed);
    //                     $("#FechaInicioRelLaboral").val(data.FechaInicioRelLaboral);
    //                     $("#RiesgoPuesto").val(data.RiesgoPuesto);
    //                     $("#TipoSangre").val(data.TipoSangre);
    //                     $("#Alergias").val(data.Alergias);
    //                     $("#EnfermedadesCronicas").val(data.EnfermedadesCronicas);
    //                     $("#EmergenciaNombre").val(data.EmergenciaNombre);
    //                     $("#EmergenciaDireccion").val(data.EmergenciaDireccion);
    //                     $("#EmergenciaTelefono").val(data.EmergenciaTelefono);
    //                     $("#EmergenciaParentesco").val(data.EmergenciaParentesco);
    //                     $("#PeriodicidadPago").val(data.PeriodicidadPago);
    //                     $("#Sindicalizado").val(data.Sindicalizado);
    //                     $("#TipoContrato").val(data.TipoContrato);
    //                     $("#TipoJornada").val(data.TipoJornada);
    //                     $("#TipoRegimen").val(data.TipoRegimen);
    //                     $("#Notas").val(data.Notas);
    //                     $("#ImgTextRuta").val(data.Foto); 
    
    //                     $("#btn-incidencia").attr('disabled',false);
    //                     $("#btn-expediente").attr('disabled', false);
    //                     if (data.Estatus == 1) {
    //                         $('#Estatus').prop('checked', true);
    //                         $('#EstatusLabel').html('Colaborador Activo');
    //                     } else {
    //                         $('#Estatus').prop('checked', false);
    //                         $('#EstatusLabel').html('Colaborador Inactivo');
    //                     }
    //                     loadExpediente(id_empleado);
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }
    //             }
    //         });
    //     }
        
    //     // IdArea
    //     var $id_area_selec2 = $('#IdArea').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         allowClear: true,
    //         placeholder: ''
    //     });
    //     $id_area_selec2.on("change", function(e) {
    //         $('#IdDepartamento').trigger('change');
    //     });
        
    //     // IdDepartamento depende de IdArea
    //     $('#IdDepartamento').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         allowClear: true,
    //         placeholder: '',
    //         ajax: {
    //             url: '../ria/inv.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: function (params) {
    
    //                 var queryParameters = {
    //                     query: params.term,
    //                     f_name: 'cxpDepartamentosArea',
    //                     id_area: $('#IdArea').val()
    //                 }
    //                 return queryParameters;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: $.map(data.data, function (item) {
    //                         return {
    //                             text: item.Departamento,
    //                             id: item.IdDepartamento
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
        
    //     $('#Estatus').on('change', function(){
    //         if ($('#Estatus').is(':checked')) {
    //             $('#EstatusLabel').html('Colaborador Activo');
    //         } else {
    //             $('#EstatusLabel').html('Colaborador Inactivo');
    //         }		
    //     });
        
    //     // Search controls, IdArea
    //     $('#s_IdArea').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         allowClear: true,
    //         placeholder: ''
    //     });
    //     // s_IdDepartamento depende de s_IdArea
    //     $('#s_IdDepartamento').select2({
    //         // theme: 'bootstrap4',
    //         language: 'es',
    //         allowClear: true,
    //         placeholder: '',
    //         ajax: {
    //             url: '../ria/inv.php',
    //             dataType: "json",
    //             type: "GET",
    //             data: function (params) {
    
    //                 var queryParameters = {
    //                     query: params.term,
    //                     f_name: 'cxpDepartamentosArea',
    //                     id_area: $('#s_IdArea').val()
    //                 }
    //                 return queryParameters;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: $.map(data.data, function (item) {
    //                         return {
    //                             text: item.Departamento,
    //                             id: item.IdDepartamento
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
    //     //evita que el scroll desaparezca cuando se abre un segundo modal
    //     $(document).on('hidden.bs.modal', function (event) {
    //         if ($('.modal:visible').length) {
    //             $('body').addClass('modal-open');
    //         }
    //     });
    //     //show modal expediente
    //     $("#btn-expediente").on('click', function () {
    //         $("#ModalExpediente").modal('show');
    //     });
        
    //     //show modal incidencias
    //     $("#btn-incidencia").on('click', function () {
    //         $("#ModalIncidencia").modal('show');
    //     });
    //     //show modal file
    //     $("#btn-img").on('click', function () {
    //         $("#ModalImg").modal('show');
    //     });
        
    //     $('#button-add-xml').on('click', function(){
    //         $('#DataModalXml').modal('show');
    //         msgBox.innerHTML = '';
    //     });
    
    //     //DROPZONE
    //     var img_file = "";
    
    //     let myDropzone = new Dropzone(".dropzone", {
    //     url:'../ria/nom_empleados_save.ria.php',
    //     maxFilesize:5,
    //     maxFiles:1,
    //     acceptedFiles:'image/png, image/jpg,',
    //     addRemoveLinks: true,
    //     dictRemoveFile: 'Cancelar'
    //     });
    
    //     myDropzone.on("addedfile", file => {
    //         img_file = file;
    //     });
    
    //     myDropzone.on("removedfile", file => {
    //         img_file = "";
    //     });
    
    //     //LIMPIA LA DROPZONE CUANDO SE CIERRA EL MODAL 
    //     $("#close-modal").on('click', function () {
    //         Dropzone.forElement('#dropzone').removeAllFiles(true) //clear the dropzone	
    //     })
        
    //     //genera la ruta de la imagen seleccionada
    //     $("#save-img").on('click', function (event) {
    //         $('#ModalImg').modal('hide');
    //         let imgData = new FormData();
    //         imgData.append('Foto', img_file);
    //         imgData.append('IdEmpleado', $('#IdEmpleado').val());
    //         /* imgData.append('Rfc', $('#Rfc').val());
    //         imgData.append('EmpresaId', $('#EmpresaId').val()); */
    //         imgData.append('op', 'saveImage');
        
    //         fetch ( '../ria/nom_empleados_save.ria.php',{
    //             method: 'POST',
    //             body: imgData
    //         }).then(res => res.json()).then(data =>{
    //             var result = data['result'];
    //             var ruta = data['ruta'];
    //             $("#Foto").attr('src', ruta +  '?' + Date.now());
    //             if (result == 1) {
    //                 $('#ImgTextRuta').val(data['ruta']);
    //                 Dropzone.forElement('#dropzone').removeAllFiles(true) //clear the dropzone	
    //             }
    //         })
    //         event.preventDefault();
    //     });
    
    //     //SAVE NEW USUARIOS AND UPDATE USUARIO
    //     $('#button-save').on('click', function(event){
    //         var parametros = $('#form-data').serialize();
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/nom_empleados_save.ria.php",
    //             data: parametros,
    //             dataType:'json',
    //             success: function(data){
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     toastr.success(data.msg);
    //                     $('#DataModal').modal('hide');
    //                     $('#grid-table').DataTable().ajax.reload();
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }
    //             }
    //         });
    //         event.preventDefault();
    //     });
    
    //     // LOAD EXPEDIENTE fAMILIAR
    //     function loadExpediente(id_empleado) {
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/nom_empleados_save.ria.php",
    //             data: {
    //                 IdEmpleado: id_empleado,
    //                 op: 'loadExpediente'
    //             },
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                     $("#table-expediente tbody").html(data.TableExpediente);
    //                 } else {
    //                     if (result == -1) {
    //                         toastr.warning(data.msg);
    //                     } else {
    //                         toastr.info(data.msg);
    //                     }
    //                 }
    //             }
    //         });
    //     }
    
    //     //SAVE EXPEDIENTE FAMILIAR
    //     $('#btn-save-expediente').on('click', function(event){
    //         var id_empleado = $("#IdEmpleado").val();
    //         var parametros = $('#form-expediente').serialize();
    //         parametros = parametros + "&op=SaveExpediente";
    //         $.ajax({
    //                 type: "POST",
    //                 url: "../ria/nom_empleados_save.ria.php",
    //                 data: parametros,
    //                 success: function(data){
    //                     var data = jQuery.parseJSON(data);
    //                     var result = data.result;
    //                     if (result == 1) {
    //                         toastr.success(data.msg);
    //                         $("#ApellidoPaternoExpediente").val('');
    //                         $("#ApellidoMaternoExpediente").val('');
    //                         $("#NombreExpediente").val('');
    //                         $("#FechaNacimientoExpediente").val('');
    //                         $("#ParentescoExpediente").val('');
    //                         $("#PoliticaAplicable").val('');
    //                         loadExpediente(id_empleado);
    //                     } else {
    //                         if (result == -1) {
    //                             toastr.warning(data.msg);
    //                         } else {
    //                             toastr.info(data.msg);
    //                         }
    //                     }
                    
    //             }
    //         });
    //     event.preventDefault();
    //     });
    
    //     //DELETE FAMILIAR EXPEDIENTE
    //     var confirmacion = "Esta seguro de eliminar este familiar";
    //     $("#table-expediente tbody").on('click', '.delete-familiar', function (){
    //         if (!confirm(confirmacion)) return false; 
    //         var id_empleado = $('#IdEmpleado').val();
    //          var id_familiar = $(this).attr('data-id');
    //         $.ajax({
    //             type: "POST",
    //             url: "../ria/nom_empleados_save.ria.php",
    //             data:{
    //                 IdEmpleado: id_empleado,
    //                 id_familiar: id_familiar,
    //                 op:'deleteFamiliar'
    //             },
    //             success: function(data){
    //                 var data = jQuery.parseJSON(data);
    //                 var result = data.result;
    //                 if (result == 1) {
    //                         toastr.success(data.msg);
    //                         loadExpediente(id_empleado);
    //                     } else {
    //                         toastr.warning(data.msg);
    //                     }
    //             }
    //         }); 
    
    //     })
    
    //     //DELETE USUARIO
    //     $('#grid-table tbody').on( 'click', '.button-delete', function () {
    //         if (!confirm(confirmacion_elimina)) return false;
            
    //         var data = table.row($(this).parents('tr')).data();
    //         if (data == undefined) {
    //             data = table.row( this ).data();
    //         }
    //         parametros = 'IdEmpleado=' + data[0] + '&op=del';
    //         $.ajax({
    //                 type: "POST",
    //                 url: "../ria/nom_empleados_save.ria.php",
    //                 data: parametros,
    //                 success: function(data){
    //                     var data = jQuery.parseJSON(data);
    //                     var result = data.result;
    //                     if (result == 1) {
    //                         toastr.success(data.msg);
    //                     } else {
    //                         if (result == -1) {
    //                             toastr.warning(data.msg);
    //                         } else {
    //                             toastr.info(data.msg);
    //                         }
    //                     }
                    
    //                 $('#DataModal').modal('hide');
    //                 $('#grid-table').DataTable().ajax.reload();
    //               }
    //         });
    //       event.preventDefault();
    //     } );
        
    //     var btn = document.getElementById('uploadBtn'),
    //       progressBar = document.getElementById('progressBar'),
    //       progressOuter = document.getElementById('progressOuter'),
    //       msgBox = document.getElementById('msgBox');
    
    //     var uploader = new ss.SimpleUpload({
    //         button: btn,
    //         dropzone: 'dragbox', // ID of element to be the drop zone
    //         url: 'nom_empleados_upload.php',
    //         name: 'uploadfile',
    //         maxUploads: 100,
    //         multiple: true,
    //         multipleSelect: true,
    //         hoverClass: 'ui-state-hover',
    //         focusClass: 'ui-state-focus',
    //         responseType: 'json',
    //         allowedExtensions: ['xml'],
    //         hoverClass: 'ui-state-hover',
    //         focusClass: 'ui-state-focus',
    //         disabledClass: 'ui-state-disabled', 
    //         startXHR: function() {
    //             progressOuter.style.display = 'block'; // make progress bar visible
    //             this.setProgressBar( progressBar );
    //         },
    //         onSubmit: function(filename, extension) {
    //             msgBox.innerHTML = ''; // empty the message box
    //             btn.innerHTML = 'Cargando...'; // change button text to "Uploading..."
                
    //             // Create the elements of our progress bar
    //             var progress = document.createElement('div'), // container for progress bar
    //                 bar = document.createElement('div'), // actual progress bar
    //                 fileSize = document.createElement('div'), // container for upload file size
    //                 wrapper = document.createElement('div'), // container for this progress bar
    //                 //declare somewhere: <div id="progressBox"></div> where you want to show the progress-bar(s)
    //                 progressBox = document.getElementById('progressBox'); //on page container for progress bars
    
    //             // Assign each element its corresponding class
    //             progress.className = 'progress progress-striped';
    //             bar.className = 'progress-bar progress-bar-success';
    //             fileSize.className = 'size';
    //             wrapper.className = 'wrapper';
    
    //             // Assemble the progress bar and add it to the page
    //             progress.appendChild(bar); 
    //             wrapper.innerHTML = '<div class="name">'+filename+'</div>'; // filename is passed to onSubmit()
    //             wrapper.appendChild(fileSize);
    //             wrapper.appendChild(progress);                                       
    //             progressBox.appendChild(wrapper); // just an element on the page to hold the progress bars    
    
    //             // Assign roles to the elements of the progress bar
    //             this.setProgressBar(bar); // will serve as the actual progress bar
    //             this.setFileSizeBox(fileSize); // display file size beside progress bar
    //             this.setProgressContainer(wrapper); // designate the containing div to be removed after upload
                
    //           },
    //         onComplete: function( filename, response ) {
    //             btn.innerHTML = 'Seleccionar archivo';
    //             progressOuter.style.display = 'none'; // hide progress bar when upload is completed
    
    //             if ( !response ) {
    //                 msgBox.innerHTML = 'No se puede cargar el archivo';
    //                 return;
    //             }
    
    //             if ( response.success === true ) {
    
    //                 msgBox.innerHTML = '';
                    
    //                 $('#DataModalXml').modal('hide');
    //                 $('#grid-table').DataTable().ajax.reload();
    //                 loadEmpleado(response.IdEmpleadoNew);
    //                 $('#DataModal').modal('show');
    //             } else {
    //                 if ( response.msg )  {
    //                     msgBox.innerHTML = escapeTags( response.msg );
    
    //                 } else {
    //                     msgBox.innerHTML = 'Ocurrio un error al cargar el archivo.';
    //                 }
    //             }
    //           },
    //         onError: function() {
    //             btn.innerHTML = 'Seleccionar archivo';
    //             progressOuter.style.display = 'none';
    //             msgBox.innerHTML = 'No se puede cargar el archivo';
    //           }
    //     });
    
    //     function escapeTags( str ) {
    //         return String( str )
    //                  .replace( /&/g, '&amp;' )
    //                  .replace( /"/g, '&quot;' )
    //                  .replace( /'/g, '&#39;' )
    //                  .replace( /</g, '&lt;' )
    //                  .replace( />/g, '&gt;' );
    //       }
          
    //       $('#button-add').on('click', function(){
    //         $('#DataModal').modal('show');
    //         $("#btn-incidencia").attr('disabled',true);
    //         $("#btn-expediente").attr('disabled', true);
    //         cleanFields();
     
    //       });
                
    //       function cleanFields() {
    //         $("#IdEmpleado").val('');
    //         $("#Foto").attr('src', '');
    //         $('#ImgTextRuta').val('');
    //         $("#ApellidoPaterno").val('');
    //         $("#ApellidoMaterno").val('');
    //         $("#Nombre").val('');
    //         $("#Sexo").val('');
    //         $("#CdCatorcena").val('QSA');
    //         $("#CdEmpleado").val('');
    //         $("#FechaNacimiento").val('');
    //         $("#FechaIngreso").val('');
    //         $("#LugarNacimiento").val('');
    //         $("#Rfc").val('');
    //         $("#Curp").val('');
    //         $("#EstadoCivil").val('');
    //         $("#Domicilio").val('');
    //         $("#Escolaridad").val('');
    //         $("#CodigoPostal").val('');
    //         $("#NumSeguridadSocial").val('');
    //         $("#NumeroCedula").val('');
    //         $("#Ciudad").val('');
    //         $("#ExpedienteDigital").val('');
    //         $("#Contratos").val('');
    //         $("#EmpresaId").val($("#EmpresaId option:first").val());
    //         $("#Tipo").val('');
    //         $("#Email").val('');
    //         $("#IdConcepto").val('');
    //         $('#Estatus').prop('checked', true);
    //         $('#IdArea').trigger('');
    //         $("#IdDepartamento").val('');
    //         $("#Puesto").val('');
    //         $("#Horario").val('');
    //         $("#SueldoMensual").val('');
    //         $("#Bono").val('');
    //         $("#SueldoImss").val('');
    //         $("#SalarioDiarioIntegrado").val('');
    //         $("#IdBanco").val('');
    //         $("#NumTarjeta").val('');
    //         $("#NumCuenta").val('');
    //         $("#NumTarjetaVales").val('');
    //         $("#ClaveInterBancaria").val('');
    //         $("#InfonavitNumero").val('');
    //         $("#InfonavitFactorDesc").val('');
    //         $("#InfonavitImporteCred").val('');
    //         $("#FonacotNumero").val('');
    //         $("#FonacotFactorDesc").val('');
    //         $("#FonacotImporteCred").val('');
    //         $("#Beneficiario").val('');
    //         $("#BeneficiarioRfc").val('');
    //         $("#BeneficiarioParentesco").val('');
    //         $("#Antiguedad").val('');
    //         $("#NumEmpleado").val('');
    //         $("#ClaveEntFed").val('');
    //         $("#FechaInicioRelLaboral").val('');
    //         $("#RiesgoPuesto").val('');
    //         $("#TipoSangre").val('');
    //         $("#Alergias").val('');
    //         $("#EnfermedadesCronicas").val('');
    //         $("#EmergenciaNombre").val('');
    //         $("#EmergenciaDireccion").val('');
    //         $("#EmergenciaTelefono").val('');
    //         $("#EmergenciaParentesco").val('');
    //         $("#PeriodicidadPago").val('');
    //         $("#Sindicalizado").val('');
    //         $("#TipoContrato").val('');
    //         $("#TipoJornada").val('');
    //         $("#TipoRegimen").val('');
    //         $("#Notas").val('');
    //     }
        
    // });
    
    


});


