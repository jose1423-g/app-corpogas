<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
segVerifyAuth($app);

// $app_title = 'Inicio';
// $index_active = 'active';

?>

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <div class="row justify-content-center">
       <div class="col-2">
            <h1 class="text-primary">PROGAS</h1>
       </div>
    </div>

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<!-- <script src="../js/main.js"></script> -->

<?php include('../layouts/main_end.php'); ?>