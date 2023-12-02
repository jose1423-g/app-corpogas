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
    
    $("#solicitudes").on('click', function () {
        // this.classList.toggle('nav-active');
        $(this).toggleClass('nav-active');
        $("#content-solicitudes").toggle('active');

    })
    



});


