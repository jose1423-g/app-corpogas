<?php 

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");

session_start();

$app = basename(__FILE__);
$app_title = 'Inicio';
$index_active = 'active';
segVerifyAuth($app);

$a_head_data = array('id','Sel','Perfil');
$a_head_data_2 = array( 'id', 'Sel','File Name','Descripcion');


?>

<?php include('../layouts/main.php');  ?>

<?php include('../layouts/main_content.php')  ?>

    <div class="row mb-100">   
        <!-- <div class="col-12"> -->
            <div class="d-block d-md-flex">
                <div class="col-5">
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table id="grid-table" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm" style="width: 100%">
                            <div class="bg-primary rounded p-2 mb-2"><h4>Lista de Perfiles</h4></div>
                            <thead>
                                <tr>
                                <?php
                                    foreach($a_head_data as $head_data) {
                                        echo "<th>$head_data</th>";
                                    }
                                ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- table clientes -->
                <input type="hidden" value="12" id="id_perfil" name="id_perfil" DISABLED>
                <input type="hidden" value="load" id="op" name="op" DISABLED>
                <input type="hidden" name="id_perfil_all" id="id_perfil_all" DISABLED>    

                <div id="content-table" class="col-7 d-none">
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table id="table-clientes" class="text-small display table table-bordered table-striped table-bordered table-hover table-condensed table-sm" style="width: 100%">
                            <thead>
                            
                                <div class="bg-primary d-flex justify-content-between rounded py-2 px-4 mb-2">
                                    <div class="d-flex">
                                        <h4 class="me-4">Aplicaciones para el perfil:</h4>
                                        <h4><span id="NombrePerfil"></span></h4>
                                    </div>
                                    <div>
                                        <button class="btn btn-danger btn-sm" id="btn-close"><i class="fas fa-times"></i></button>        
                                    </div>
                                </div>
                                <div class="btn-group mb-2">
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Mostrar Solo los conceptos seleccionados" class="btn btn-sm btn-primary active" id="button-show-sel">Solo seleccionados</button>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Mostrar Todos los conceptos" class="btn btn-sm btn-primary" id="button-show-all">Todos</button>
                                </div>
                                <tr>
                                <?php
                                    foreach($a_head_data_2 as $head_data) {
                                        echo "<th>$head_data</th>";
                                    }
                                ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>

<?php include('../layouts/main_content_end.php')  ?>

<?php include('../layouts/footer.php'); ?>
    <!-- script -->
<script src="../js/seg_perfilesaplicaciones.js"></script>

<?php include('../layouts/main_end.php'); ?>