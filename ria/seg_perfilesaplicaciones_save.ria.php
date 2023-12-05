<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
// require_once("$SYS_ROOT/php/cxc/cxc.inc.php");
	
session_start();

// recupera datos
// header
$id_user = SessGetUserId();

$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

$msg = '';
$result = 0;


$id_perfil = (isset($_REQUEST['id_perfil'])) ? $_REQUEST['id_perfil'] : '';
$s_is_show_all = (isset($_GET['s_is_show_all'])) ? $_GET['s_is_show_all'] : '';
$check_app = (isset($_REQUEST['check_app'])) ? $_REQUEST['check_app'] : '';
$id_app = (isset($_REQUEST['id_app'])) ? $_REQUEST['id_app'] : '';


$msg = '';
$html_btn = '';
$result = 0;

// $a_perfiles = array('1','2','3');

//  if (!in_array(4, $a_perfiles)) {
// 	echo "true";
// 	exit();
//  } else {
// 	echo "false";
// 	exit();
//  }

if ($op == 'load') {
	if (!strlen($id_perfil)) {
		$msg = 'Hubo un error, el perfil no fue seleccionado';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {

		// $a_values = array();
		//muestra los seleccionados
		if (strlen($id_perfil)) {
			$qry = "SELECT IdApp_fk FROM Seg_PerfilesAplicaciones WHERE IdUsuarioPerfil_fk = $id_perfil";
			$a_perfiles = DbQryToArray($qry);
		}
		
		$qry = "SELECT IdApp, IdApp AS Sel, FileName, Descripcion
				FROM Seg_Aplicaciones WHERE Es_activa = 1"; 

		$a_data_b = DbQryToArray($qry, true);
		$a_data = array();
		$val = array();

		foreach($a_data_b as $a_dt) {
			$a_data_line = array();
			$a_data_line['IdApp'] = (string) $a_dt['IdApp'];
		
			$id_app = $a_dt['Sel'];

			$val['IdApp_fk'] = $id_app;

			// echo "val <pre>". print_r($val, true) ."</pre>";
			// exit();
			$checked = 0;
			
			if (in_array($val, $a_perfiles)) {
				$checked = 1;
			}
		
			if ($s_is_show_all == 1 or $checked == 1) {
				$a_data_line['Sel'] = '';
				if ($checked == 1) {
					$a_data_line['Sel'] = (string) "<input type='checkbox' data-id='".$id_app."' class='idapp' name='group_checkbox' value='". $id_app ."' checked>";
				} else {
					$a_data_line['Sel'] = (string) "<input type='checkbox' data-id='".$id_app."' class='idapp' name='group_checkbox' value='". $id_app ."'>";
				}
				$a_data_line['FileName'] = (string) utf8_encode($a_dt['FileName']);
				$a_data_line['Descripcion'] = (string) utf8_encode($a_dt['Descripcion']);
				$a_data[] = $a_data_line;
			}
		}
			$json =  json_encode(array('data' => $a_data), true);
			echo $json;		
	} 
	
} elseif ($op == 'savePerfil') {
	if (!strlen($id_perfil)) {
		$msg = 'Hubo un error, el perfil no fue seleccionado';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		
		if ($check_app == 1) {
			$qry = "INSERT INTO Seg_PerfilesAplicaciones ( IdApp_fk, IdUsuarioPerfil_fk )VALUES($id_app, $id_perfil)";
			$res_db = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_db)) {
				$msg = 'Error SQL:' . $res_db;
			} else {
				if (!$res_db) {
					$msg = 'La no fue seleccionada';
					$result = -1;
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					exit();
				} else {
					$result = 1;
					$msg = 'Dato guardado';
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				}
			}
		} else {
			/* SELECT * FROM Seg_PerfilesAplicaciones WHERE IdUsuarioPerfil = 1 AND IdApp = 71 */
			$qry = "DELETE FROM Seg_PerfilesAplicaciones WHERE IdUsuarioPerfil_fk = $id_perfil AND IdApp_fk = $id_app";
			$res_db = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_db)) {
				$msg = 'Error SQL:' . $res_db;
			} else {
				if (!$res_db) {
					$msg = 'No borrado';
					$result = -1;
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				} else {
					$result = 1;
					$msg = 'Dato borrado';
					$a_ret =  array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					// exit();
				}
			}
		} 		
	}
} 

// $a_ret = array('result' => $result, 'msg' => $msg, 'IdPerfil' => $id_perfil);
// echo json_encode($a_ret);
?>