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

function leerExcel($rutaArchivo) {
	$objPHPExcel = PHPExcel_IOFactory::load($rutaArchivo);
    // $inputFileType = PHPExcel_IOFactory::identify($rutaArchivo);        
    // $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    // $objPHPExcel = $objReader->load($rutaArchivo);
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn(); // letra columna
	$highestCol = PHPExcel_Cell::columnIndexFromString($highestColumn); // numero columna

    $col_b = 'B';
    $col_e = 'E';
    $col_f = 'F';

    $a_gerentes = [];

    


    for ($row = 1; $row <= $highestRow; $row++){
		$col = 0;

        $num_estacion = '';        
        $correo_gerente = '';
        $nombre = '';
        $apellido_p = '';
        $apellido_m = '';

		for ($col = 0; $col <= $highestCol; $col++) {
			$colName = PHPExcel_Cell::stringFromColumnIndex($col);
			$valor = $sheet->getCell($colName . $row)->getValue();
			
            // echo "Row: $row, Col: $colName, Col Num: $col, valor: $valor" . "<br>";

            if ($valor === 'E.S' || $valor === 'Gerente' || $valor === 'Correo de GPV') {
                continue 2;
            }

            if ($colName == $col_b && strlen($valor)) {                
                // echo "B"."Row". $row. "Col ".$colName ." Col Num". $col ."  valor ".$valor."<br>";
                $longitud =  strlen($valor);                
                if ($longitud == 2) {                    
                    $num_estacion = "000".$valor;
                } else if ($longitud == 3) {
                    $num_estacion = "00".$valor;
                } else if ($longitud == 4) {                    
                    $num_estacion = "0".$valor;
                } else if ($longitud == 5) {
                    $num_estacion = $valor;
                }
            }

            if ($colName == $col_e && strlen($valor)) {
                $a_nombre = explode(' ', $valor);
                $size =  count($a_nombre);

                if ($size == 1) {
                    
                    $nombre = $a_nombre[0];
                    $apellido_p = '';
                    $apellido_m = '';

                } else if ($size == 3) {
                    
                    $nombre = $a_nombre[0];
                    $apellido_p = $a_nombre[1];
                    $apellido_m = $a_nombre[2];

                } else if ($size == 4) {
                    
                    $nombre = $a_nombre[0];
                    $apellido_p = $a_nombre[1];
                    $apellido_m = $a_nombre[2];

                } else if ($size == 5) {
                    
                    $nombre = $a_nombre[0]." ".$a_nombre[1];
                    $apellido_p = $a_nombre[2]." ".$a_nombre[3];
                    $apellido_m = $a_nombre[4];

                } else if ($size == 6) {

                    $nombre = $a_nombre[0]." ".$a_nombre[1];
                    $apellido_p = $a_nombre[2]." ".$a_nombre[3];
                    $apellido_m = $a_nombre[4]." ".$a_nombre[5];
                }
            }

            if ($colName == $col_f && strlen($valor)) {                
                $correo_gerente = $valor;
            }
		}

        if ($num_estacion || $nombre || $correo_gerente) {
            $a_gerentes[] = ['ES' => $num_estacion, 'Nombre' => $nombre, 'ApellidoP' => $apellido_p, 'ApellidoM' => $apellido_m, 'email' => $correo_gerente];
        }
    }
    
    $i = 0;
    foreach($a_gerentes as $item) {
        $no_estacion =  $item['ES'];
        $name = $item['Nombre']." ".$item['ApellidoP']." ".$item['ApellidoM'];
        $email =  $item['email'];
        
        $qry = "SELECT t1.IdUsuario, t3.IdEstacion, t3.NoEstacion, t1.Nombre, t1.ApellidoPaterno, t1.ApellidoMaterno
        FROM seg_usuarios t1 
        LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
        LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
        WHERE CONCAT(t1.Nombre,' ',t1.ApellidoPaterno,' ',t1.ApellidoMaterno) LIKE '%$name%'AND t3.NoEstacion = $no_estacion";
        $a_qry = DbQryToRow($qry);

        if (empty($a_qry)) {

            $qry = "SELECT t1.IdUsuario, t3.IdEstacion, t3.NoEstacion, CONCAT(t1.Nombre,' ',t1.ApellidoPaterno,' ',t1.ApellidoMaterno)AS nombre_completo
                FROM seg_usuarios t1 
                LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
                LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
                WHERE t3.NoEstacion = $no_estacion AND t1.UsuarioPerfilId_fk = 13";
            $a_data = DbQryToRow($qry);
            $id_usuario =  $a_data['IdUsuario'];
            $namef = $item['Nombre'];
            $apellidop = $item['ApellidoP'];
            $apellidom =  $item['ApellidoM'];

            if (strlen($id_usuario)) {
                $qry = "UPDATE seg_usuarios SET Nombre = '$namef', ApellidoPaterno = '$apellidop', ApellidoMaterno = '$apellidom', Email = '$email' WHERE IdUsuario = $id_usuario".";";
                echo $qry."<br>";
            }
            
        } else {
            echo "<pre>".print_r($a_qry, true)."</pre>";
        } 
    }

}

// $rutaArchivo = (isset($_FILES['excel'])) ? $_FILES['excel'] : '';
// $absolutePath = realpath($rutaArchivo);

// Ruta del archivo Excel
// $rutaArchivo = 'C:/Downloads/BASE ESTACIONES 20240829 Portal de Refacciones.xlsx';
$rutaArchivo = 'BASE ESTACIONES 20240829 Portal de Refacciones.xlsx';

// Llamar a la función para leer y mostrar el contenido del archivo Excel
leerExcel($rutaArchivo);