$(document).ready(function() {
    $("#btn_submit").on('click', function () {
        let file = document.getElementById('excel');
        let name_file = file.files[0];

        if (!name_file) {
            alert('Selecciona un archivo');
            return;
        } 

        $('#showMessages').removeClass('d-none');
        $('#btn_submit').prop('disabled', true);

        let formData = new FormData();
        formData.append('excel', name_file);  // Añadir el archivo
        formData.append('op', 'upload');
                
        $.ajax({
            type: "POST",
            url: "../../reportes_excel/read_excel.php",
            data: formData,
            contentType: false, // No establecer el tipo de contenido, dejar que jQuery lo maneje
            processData: false,
            success: function (data){                                
                var data = jQuery.parseJSON(data);
                let result =  data.file_resp.result;
                if (result == -1) {
                    alert(data.msg);
                } else {
                    $('#showMessages').addClass('d-none');
                    $('#ShowSuccess').removeClass('d-none');
                    $('#btn_submit').prop('disabled', false);
                    // console.log(data.Estaciones);
                    // console.log(data.Supervisores);
                    // console.log(data.Users);
                    // console.log(data.file_resp);
                }
            }
        })
    })

});