<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

// $app = basename(__FILE__);
// segVerifyAuth($app);

?>

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <div class="row justify-content-center">
        <div class="col-5 bg-white rounded-2 shadow-lg">
            <h1 class="text-danger">Error 404</h1>
            <p>Disculpa, parece que no tienes los privilegios necesarios para acceder a esta aplicación en particular. Si crees que deberías tener acceso, por favor, ponte en contacto con el equipo de TI para revisar tus permisos</p>
        </div>
    </div>

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>

<!-- script -->
<!-- <script src="../js/main.js"></script> -->

<?php include('../layouts/main_end.php'); ?>