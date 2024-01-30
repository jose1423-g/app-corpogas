<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/file_uploader.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// agrega o guarda datos del usuario
$id_user = SessGetUserId();	

// recupera datos
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$nombre_refaccion = (isset($_REQUEST['NombreRefaccion'])) ? $_REQUEST['NombreRefaccion'] : "";
$referencia = (isset($_REQUEST['Referencia'])) ? $_REQUEST['Referencia'] : "";
$no_serie = (isset($_REQUEST['NoSerie'])) ? $_REQUEST['NoSerie'] : "";
$id_categoria = (isset($_REQUEST['IdCategoria_fk'])) ? $_REQUEST['IdCategoria_fk'] : "";
$img = (isset($_FILES['uploadedfile'])) ? $_FILES['uploadedfile'] : "";
$es_activo = (isset($_REQUEST['EsActivo'])) ? 1 : 0;


$msg = "";
$result = 0;

// $fecha_hoy = "";
// $fecha_hoy =  DtDbToday($fecha_hoy);

// $minutos = date('i');

if ($op == 'save') {
	if (!strlen($nombre_refaccion)) {
		$msg = "El campo nombre refaccion es necesario";
		$result = -1;
	} elseif (!strlen($referencia)) {
		$msg = "El campo referencia es necesario";
		$result = -1;
	// } elseif (!strlen($no_serie)) {
	// 	$msg = "El campo No serie es necesario";
	// 	$result = -1;
	} elseif (!strlen($id_categoria)) {
		$msg = "El campo categoria es necesario";
		$result = -1;
	} elseif (empty($img)) {
		$msg = "El campo imagen es necesario";
		$result = -1;
	} elseif (!strlen($id_user)){
		$msg = "Su session ha expirado";
		$result = -1;
	} else {
		
		$nombre_refaccion = utf8_decode($nombre_refaccion);
		$referencia = utf8_decode($referencia);

			//tiene algo				
		if(!empty($_FILES)){
			$new_name = $_FILES['name'];
			$a_result = UploadFileDoc($_FILES, $new_name, "../images/products/");
			$msg = $a_result['msg'];
			$result = $a_result['result'];
			$new_file_name = $a_result['new_file_name'];

			if ($result != 1) {
				$new_file_name = '';
			}
		} 

		if(!strlen($no_serie)){
			$no_serie = 'NULL';
		}
		
		$qry = "INSERT INTO productos (NombreRefaccion, Referencia, NoSerie, IdCategoria_fk, img, EsActivo) 
				VALUES ('$nombre_refaccion', '$referencia', $no_serie, $id_categoria, '$new_file_name', $es_activo)";
			$result = DbExecute($qry, true);
			if (is_string($result)) {
				$msg = "Hubo un error al agregar el producto". $result;
				$result = -1;
			} else {
				if (!$result) {
					$msg = "Hubo un error al agregar el producto intente nueva mente";
					$result = -1;
				} else {
					$msg = "El productos se agregó correctamente";
					$result = 1;
				}
			}
	}
}
$a_ret = array('result' => $result, 'msg' => $msg);
echo json_encode($a_ret);
?>