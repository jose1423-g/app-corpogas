<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
	
session_start();

// guarda los datos de configuracion FTPConnType
$empresa_id = 1; // fijo 1

// recupera datos
// header
$id_user = SessGetUserId();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$ftp_ip = (isset($_REQUEST['FTPIp'])) ? $_REQUEST['FTPIp'] : '';
$ftp_user = (isset($_REQUEST['FTPUser'])) ? $_REQUEST['FTPUser'] : '';
$ftp_pass = (isset($_REQUEST['FTPPass'])) ? $_REQUEST['FTPPass'] : '';
$ftp_port = (isset($_REQUEST['FTPPort'])) ? $_REQUEST['FTPPort'] : '';
$ftp_schedule = (isset($_REQUEST['FTPSchedule'])) ? $_REQUEST['FTPSchedule'] : '';
$ftp_conn_type = (isset($_REQUEST['FTPConnType'])) ? $_REQUEST['FTPConnType'] : '';
$ftp_service_type = (isset($_REQUEST['FTPServiceType'])) ? $_REQUEST['FTPServiceType'] : '';
$ftp_folder = (isset($_REQUEST['FTPFolder'])) ? $_REQUEST['FTPFolder'] : '';
$id_estacion = (isset($_REQUEST['IdEstacion'])) ? $_REQUEST['IdEstacion'] : '';
$usar_ftp_estacion = (isset($_REQUEST['UsarFTPEstacion'])) ? $_REQUEST['UsarFTPEstacion'] : 0;

$a_conn_types = array(
	1 => 'G500',
	2 => 'General'
);

$msg = '';
$result = 0;

if ($op == 'save') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$ftp_conn_type = (strlen($ftp_conn_type)) ? $ftp_conn_type : 1; // 1 por default
		if ($ftp_conn_type == 1) {
			$ftp_folder = ''; // cuando la conexion es G500 el folder debe quedar vacio
		}
		
		// trata de leer la hora del archivo .timer desde systemd
		// No se edita, solo se guarda para mostrarla al usuario
		$filename_timer = "/etc/systemd/system/ftdox-xml-send.timer";
		if (is_readable($filename_timer)) {
			$timer_lines = file($filename_timer);
			foreach($timer_lines as $line_no => $line) {
				$a_line_elems = explode("=", $line);
				if ($a_line_elems[0] == 'OnCalendar') {
					if (strlen($a_line_elems[1])) {
						$ftp_schedule = $a_line_elems[1];
					}
				}
			}
		}
		
		$qry = "UPDATE empresa
				SET FTPIp = '$ftp_ip',
					FTPUser = '$ftp_user',
					FTPPass = '$ftp_pass',
					FTPPort = '$ftp_port',
					FTPSchedule = '$ftp_schedule',
					FTPConnType = $ftp_conn_type,
					FTPServiceType = '$ftp_service_type',
					FTPFolder = '$ftp_folder'
				WHERE EmpresaID = $empresa_id";
		$res = DbExecute($qry, true);
		if ($res) {
			if (is_string($res)) {
				$msg = "Hubo un error al guardar los cambios";
			} else {
				$msg = "Se guardaron los cambios";
				$result = 1;
			}
		} else {
			$msg = "Hubo un error al guardar los cambios";
			$result = -1;
		}
	}
	$a_ret = array('result' => $result, 'msg' => $msg);
	echo json_encode($a_ret);
	exit();
	
} elseif ($op == 'load') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		
		$qry = "SELECT EmpresaID, FTPIp, FTPUser, FTPPass, FTPPort, FTPConnType, FTPFolder, FTPSchedule, FTPServiceType
				FROM empresa
				WHERE EmpresaID = $empresa_id";
		$a_row = DbQRyToRow($qry);
		
		// trata de leer la hora del archivo .timer desde systemd
		// No se edita, solo se guarda para mostrarla al usuario
		$ftp_schedule = "";
		$filename_timer = "/etc/systemd/system/ftdox-xml-send.timer";
		if (is_readable($filename_timer)) {
			$timer_lines = file($filename_timer);
			foreach($timer_lines as $line_no => $line) {
				$a_line_elems = explode("=", $line);
				if ($a_line_elems[0] == 'OnCalendar') {
					if (strlen($a_line_elems[1])) {
						$ftp_schedule = $a_line_elems[1];
					}
				}
			}
		}
		
		$ftp_conn_type = $a_row['FTPConnType'];
		$ftp_conn_type_desc = (isset($a_conn_types[$ftp_conn_type])) ? $a_conn_types[$ftp_conn_type] : '?';
		$a_row['FTPConnTypeDesc'] = $ftp_conn_type_desc;
		if (strlen($ftp_schedule)) {
			$a_row['FTPSchedule'] = $ftp_schedule;
		}
		$a_row['FTPSchedule'] = TmDbLgToShow($a_row['FTPSchedule'], 'H:i');

		$a_row['result'] = 1;
		$a_row['msg'] = '';
		
		$a_row = utf8ize($a_row);
		echo json_encode($a_row);
		exit();
	}
	$a_ret = array('result' => $result, 'msg' => $msg);
	echo json_encode($a_ret);
	exit();
} elseif ($op == 'saveEst') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} elseif (!strlen($id_estacion)) {
		$msg = 'La estacion no fue seleccionada correctamente';
		$result = -1;
	} else {
		$ftp_conn_type = (strlen($ftp_conn_type)) ? $ftp_conn_type : 1; // 1 por default
		if ($ftp_conn_type == 1) {
			$ftp_folder = ''; // cuando la conexion es G500 el folder debe quedar vacio
		}
		$usar_ftp_estacion = ($usar_ftp_estacion == 1) ? 1 : 0;
		if ($usar_ftp_estacion == 0) {
			$ftp_ip = '';
			$ftp_user = '';
			$ftp_pass = '';
			$ftp_port = '';
			$ftp_conn_type = "NULL";
			$ftp_service_type = '';
			$ftp_folder = '';
		}
		$qry = "UPDATE estaciones
				SET FTPIp = '$ftp_ip',
					FTPUser = '$ftp_user',
					FTPPass = '$ftp_pass',
					FTPPort = '$ftp_port',
					FTPSchedule = '$ftp_schedule',
					FTPConnType = $ftp_conn_type,
					FTPServiceType = '$ftp_service_type',
					FTPFolder = '$ftp_folder',
					UsarFTPEstacion = $usar_ftp_estacion
				WHERE IdEstacion = $id_estacion";
		$res = DbExecute($qry, true);
		if ($res) {
			if (is_string($res)) {
				$msg = "Hubo un error al guardar los cambios $res";
			} else {
				$msg = "Se guardaron los cambios";
				$result = 1;
			}
		} else {
			$msg = "Hubo un error al guardar los cambios >";
			$result = -1;
		}
	}
	$a_ret = array('result' => $result, 'msg' => $msg);
	echo json_encode($a_ret);
	exit();
	
} elseif ($op == 'loadEst') {
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} elseif (!strlen($id_estacion)) {
		$msg = 'La estacion no fue seleccionada correctamente';
		$result = -1;
	} else {
		
		$qry = "SELECT IdEstacion, UsarFTPEstacion, FTPIp, FTPUser, FTPPass, FTPPort, FTPConnType, FTPFolder, FTPSchedule, FTPServiceType
				FROM estaciones
				WHERE IdEstacion = $id_estacion";
		$a_row = DbQRyToRow($qry);
		
		$usar_ftp_estacion = $a_row['UsarFTPEstacion'];
		$a_row['UsarFTPEstacion'] = ($usar_ftp_estacion == 1) ? 1 : 0;
		$ftp_conn_type = $a_row['FTPConnType'];
		$ftp_conn_type_desc = (isset($a_conn_types[$ftp_conn_type])) ? $a_conn_types[$ftp_conn_type] : '?';
		$a_row['FTPConnTypeDesc'] = $ftp_conn_type_desc;
		$a_row['FTPSchedule'] = TmDbLgToShow($a_row['FTPSchedule'], 'H:i');
		$a_row['result'] = 1;
		$a_row['msg'] = '';
		
		$a_row = utf8ize($a_row);
		echo json_encode($a_row);
		exit();
	}
	$a_ret = array('result' => $result, 'msg' => $msg);
	echo json_encode($a_ret);
	exit();
}


?>