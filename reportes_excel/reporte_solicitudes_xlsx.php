<?php
require_once('../vendor/PHPExcel/Classes/PHPExcel.php');
require('../vendor/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/archivospdf/solicitud.php");


session_start();

// agrega o guarda datos del usuario
$id_user = SessGetUserId();	

// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$estacion = (isset($_REQUEST['s_estacion'])) ? $_REQUEST['s_estacion'] : "";
$fecha_desde = (isset($_REQUEST['s_FechaDesde'])) ? $_REQUEST['s_FechaDesde'] : "";
$fecha_hasta = (isset($_REQUEST['s_FechaHasta'])) ? $_REQUEST['s_FechaHasta'] : "";
$estatus = (isset($_REQUEST['s_mostrar'])) ? $_REQUEST['s_mostrar'] : "";

$msg = "";
$result = '';

if ($op == 'get_exel') {

    $show_estatus = '';

    if (strlen($estatus)) {
        $show_estatus = "AND t1.Estatus = $estatus";
    }

    $fecha_desde = DtShowToDb($fecha_desde);
    $fecha_hasta = DtShowToDb($fecha_hasta);

    $qry = "SELECT t1.Folio, CONCAT(t2.EstacionServicio ,'  ', t2.NoEstacion) EstacionServicio, t1.IdCategoria_fk, t1.fecha, t1.Estatus, t1.Observaciones 
        FROM solicitudes t1
        LEFT JOIN estaciones t2 ON t1.IdEstacion_fk = t2.IdEstacion
        WHERE t1.IdCategoria_fk IS NOT NULL AND t1.Estatus > 1 
        AND t1.fecha >= $fecha_desde AND t1.fecha <= $fecha_hasta
        $show_estatus            
        AND t1.IdEstacion_fk = $estacion";
        $a_data = DbQryToArray($qry);

        foreach ($a_data as $key => $value) {                

            $id_categoria  =  $value['IdCategoria_fk'];

            $a_data_categoria = array();
            $qry = "SELECT Categoria FROM productos_categorias WHERE IdCategoria IN($id_categoria)";													
            $a_categoria =  DbQryToArray($qry);
            foreach ($a_categoria as $row){
                $valor =  $row['Categoria'];
                array_push($a_data_categoria, $valor);
            }
            $categorias = implode(', ', $a_data_categoria);
            
            $a_data[$key]['IdCategoria_fk'] = $categorias;

            $fecha = DtDbToShow($value['fecha']);

            $a_data[$key]['fecha'] = $fecha;

            if ($value['Estatus'] == 2) {
                $a_data[$key]['Estatus'] = 'Pendiente Revision';
            } else if ($value['Estatus'] == 3) {
                $a_data[$key]['Estatus'] = 'Rechazada';
            } else if ($value['Estatus'] == 4) {
                $a_data[$key]['Estatus'] = 'Abierta';
            }
            
        }

    $spreadsheet = new PHPExcel();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    $rowArray=['Folio', 'Estacion', 'Categoria', 'Fecha', 'Estatus', 'Observaciones'];
    $spreadsheet->getActiveSheet() ->fromArray($rowArray, null);
    $rowIndex = 2;

    foreach ($a_data as $row) {
        $columnIndex = 'A';
        foreach ($row as $cell) {
            $activeWorksheet->setCellValue($columnIndex . $rowIndex, $cell);
            $columnIndex++;
        }
    $rowIndex++;
    } 
    
    $activeWorksheet->getColumnDimension('A')->setWidth(20);
    $activeWorksheet->getColumnDimension('B')->setWidth(50);
    $activeWorksheet->getColumnDimension('C')->setWidth(20);
    $activeWorksheet->getColumnDimension('D')->setWidth(20);
    $activeWorksheet->getColumnDimension('E')->setWidth(20);
    $activeWorksheet->getColumnDimension('F')->setWidth(100);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_solicitudes.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new PHPExcel_Writer_Excel2007($spreadsheet);
    $writer->save('php://output');
    exit;

}

?>