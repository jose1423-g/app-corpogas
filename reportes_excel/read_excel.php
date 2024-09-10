<?php 

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../vendor/PHPExcel/Classes/PHPExcel.php');
require('../vendor/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/archivospdf/solicitud.php");

function leerExcel($rutaArchivo) {
    
    $inputFileType = PHPExcel_IOFactory::identify($rutaArchivo);        
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($rutaArchivo);
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();


    // foreach ($highestRow as $row) {
    for ($row = 1; $row <= $highestRow; $row++){
        $columnIndex = 'A';
        $sheet->getCell($columnIndex .$row)->getValue();
        $columnIndex++;
    } 

    // echo "holis".  $rutaArchivo;
}

// $rutaArchivo = (isset($_FILES['excel'])) ? $_FILES['excel'] : '';
// $absolutePath = realpath($rutaArchivo);

// Ruta del archivo Excel
$rutaArchivo = 'C:/Downloads/BASE ESTACIONES 20240829 Portal de Refacciones.xlsx';

// Llamar a la función para leer y mostrar el contenido del archivo Excel
leerExcel($rutaArchivo);