<?php 

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/archivospdf/solicitud.php");
require_once("$SYS_ROOT/vendor/PHPExcel/Classes/PHPExcel.php");


$archivo = (isset($_FILES['excel'])) ? $_FILES['excel'] : '';
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

// echo "<pre>". print_r($archivo, true) ."<pre>";

if ($op == 'upload') {
    if (!strlen($archivo['name'])) {
        // $a_ret = ['msg' => 'Por favor sube un archivo', 'result' => -1];        
        // echo json_encode($a_ret);
        // exit();
    }

    $archivoTmp = $archivo['tmp_name'];
    $file_name =  $archivo['name'];
    $directorio = '../archivospdf/';
    $ruta_final = $directorio . $file_name;
    $bander = 0;
    if (move_uploaded_file($archivoTmp, $ruta_final)) {
        // echo "Archivo subido y guardado exitosamente en: " . $ruta_final;
        $bandera = 1;
    } else {
        $bandera;
        // echo "Error al mover el archivo.";
    }

    $response = [
        'Bandera' => $bandera,
        'Users' => $archivo['name'],
        'Supervisores' => $archivo['name'],
        'Estaciones' => $archivo['name'],
    ];

    echo json_encode($response);
}

    


// $rutaArchivo = (isset($_FILES['excel'])) ? $_FILES['excel'] : '';

// Ruta del archivo Excel
// $rutaArchivo = 'BASE ESTACIONES 20240829 Portal de Refacciones.xlsx';

// Llamar a la función para leer y mostrar el contenido del archivo Excel
// $resp = leerExcel($rutaArchivo);
// echo $resp."<br>";