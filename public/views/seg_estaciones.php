<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Inicio';
$index_active = 'active';
$valor =  segVerifyAuth($app);
// echo $valor;
// exit();

?>

<?php include('../layouts/main.php');  ?>
<?php include('../layouts/main_content.php')  ?>

    <div class="row">
        <div class="col-12">
            <h1>Seg estaciones</h1>
            <div class="h-50">
                <P>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>holis1 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>holis2 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>holis2 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>holis2 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="h-25">
                <P>holis2 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque!</P>
            </div>
            <div class="">
                <P>holis6 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Assumenda, ut fuga ea, nostrum dolorem soluta earum aliquam recusandae aliquid eos ex ipsa repellendus voluptates doloremque molestias obcaecati adipisci quae eaque! holis</P>
            </div>
        </div>
    </div>

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/seg_estaciones.js"></script>
<?php include('../layouts/main_end.php'); ?>