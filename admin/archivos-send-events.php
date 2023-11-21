<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/sftp.inc.php");
require_once("$SYS_ROOT/php/knl/ftp.inc.php");

// revisar cada estacion a ver si es hora de mandar archivos
// revisa si hay archivos pendientes de enviar (estatus 0, null o 1), ordenados por estacion
// recorre los archivos a enviar separados por estacion
// busca los datos de comunicacion con la estacion (ftp, usuario, pass, etc)
// envia los archivos
// si se envian correctamente, se marcan como 2 en la BD, con la fecha y la hora de envio
// Si hay un error con cada archivo, se marca como 1 en la BD, no se guarda le fecha y hora de envio

// fixed config
$empresa_id = 1;
$local_dir = "../upload/files/";

function sendAllPendFiles($id_file_par = "", $v = "") {
	global $empresa_id;
	global $local_dir;
	
	// obtiene configuracion global
	$qry = "SELECT FTPIp, FTPUser, FTPPass, FTPPort, FTPConnType, FTPFolder, FTPSchedule, FTPServiceType
			FROM empresa
			WHERE EmpresaID = $empresa_id";
	$a_global_config = DbQryToRow($qry);
	
	if ($v == 'v') { echo "Leyendo archivos a enviar..."; }
	if (strlen($id_file_par)) {
		// lee archivo individual y datos de la estacion
		$qry = "SELECT t1.IdFile, t1.IdEstacion, t2.PL, t1.FileName
				FROM archivos t1
				LEFT JOIN estaciones t2 ON t1.IdEstacion = t2.IdEstacion
				WHERE t1.IdFile = $id_file_par";
	} else {
		// lee archivos y datos de la estacion
		$qry = "SELECT t1.IdFile, t1.IdEstacion, t2.PL, t1.FileName
				FROM archivos t1
				LEFT JOIN estaciones t2 ON t1.IdEstacion = t2.IdEstacion
				WHERE COALESCE(t1.EstatusEnvio, 0) IN(0, 1)";
	}
	$a_files = DbQryToArray($qry, true);

	// config
	$a_config = $a_global_config; // por ahora solo toma la configuracion global

	// prepara archivos y obtiene estaciones para buscar configuracion especifca
	$a_files_all = array();
	$a_files_all_responses = array();
	foreach($a_files as $a_file) {
		if ($v == 'v') { echo "+"; }
		$id_file = $a_file['IdFile'];
		$id_estacion = $a_file['IdEstacion'];
		$filename = $a_file['FileName'];
		$pl = $a_file['PL'];
		$path = $local_dir . $pl . "/";
		$a_file_tmp = array('IdFile' => $id_file, 'FileName' => $filename, 'Path' => $path);
		$a_files_estacion = (isset($a_files_all[$id_estacion])) ? $a_files_all[$id_estacion] : array();
		$a_files_estacion[$id_file] = $a_file_tmp;
		$a_files_all[$id_estacion] = $a_files_estacion;
	}
	if ($v == 'v') { echo " Ok" . PHP_EOL; }

	foreach($a_files_all as $id_estacion => $a_files_estacion) {
		$qry = "SELECT IdEstacion, PL, UsarFTPEstacion,
					FTPIp, FTPUser, FTPPass, FTPPort, FTPConnType, FTPFolder, FTPSchedule, FTPServiceType
				FROM estaciones
				WHERE IdEstacion = $id_estacion";
		$a_config_est = DbQRyToRow($qry);
		$pl = $a_config_est['PL'];
		if ($v == 'v') { echo "Solicitando envio de Estacion $pl" . PHP_EOL; }
		if ($a_config_est['UsarFTPEstacion'] == 1) {
			unset($a_config_est['IdEstacion']);
			unset($a_config_est['UsarFTPEstacion']);
			$a_config = $a_config_est;
		}
		if ($a_config['FTPConnType'] == 1) {
			$a_config['FTPFolder'] = $pl;
		}
		
		if ($a_config['FTPServiceType'] == 'SFTP') {
			// sftp
			$result_ftp = sftpMultiPutFile($a_config, $a_files_estacion, $v);
		} else {
			// ftp por default
			$result_ftp = ftpMultiPutFile($a_config, $a_files_estacion, $v);
		}
		$a_result = $result_ftp;
		
		if (isset($a_result['result']) and $a_result['result'] == -1) {
			// se sale, ya no debe seguir enviando archivos
			break;
		}
		
		// marca cada archivo en la base de datos segun el resultado
		$fecha = DtDbToday();
		$hora = TmDbStamp();
		$a_files_response = (isset($result_ftp['a_files_response'])) ? $result_ftp['a_files_response'] : array();
		foreach($a_files_response as $id_file => $a_file_res) {
			$res_file = $a_file_res[0];
			$res_file_msg = $a_file_res[1];
			$res_filename = $a_file_res[2];
			$estatus_envio = ($res_file == 1) ? 2 : 1; // 1 es que se intento enviar pero dio error, 2 quiere decir que se envio bien
			$msg_file = $res_filename . ", " . $res_file_msg;
			if ($v == 'v') { echo $msg_file . PHP_EOL; }
			if (strlen($id_file)) {
				$qry = "UPDATE archivos
						SET EstatusEnvio = $estatus_envio,
							FechaEnvio = $fecha,
							HoraEnvio = '$hora',
							MensajeEnvio = '$res_file_msg'
						WHERE IdFile = $id_file";
				$res_upd = DbExecute($qry, true);
				if (is_string($res_upd)) {
					$msg_file = ". Error al actualizar el log: " . $res_upd;
					if ($v == 'v') { echo $msg_file . PHP_EOL; }
				} elseif (!$res_upd) {
					$msg_file = ". Error al actualizar el log del archivo";
					if ($v == 'v') { echo $msg_file . PHP_EOL; }
				}
			}
			$a_files_all_responses[$id_file] = $msg_file;
		}
		if (empty($a_files_response) and strlen($id_file_par)) {
			$estatus_envio = ($a_result['result'] == 1) ? 2 : 1;
			$res_file_msg = (isset($a_result['msg'])) ? $a_result['msg'] : (($estatus_envio == 1) ? "No enviado" : "Enviado");
			$qry = "UPDATE archivos
					SET EstatusEnvio = $estatus_envio,
						FechaEnvio = $fecha,
						HoraEnvio = '$hora',
						MensajeEnvio = '$res_file_msg'
					WHERE IdFile = $id_file_par";
			$res_upd = DbExecute($qry, true);
		}
	}
	if (empty($a_files_all_responses)) {
		$a_files_all_responses[-1] = "No hay archivos pendientes de envio. No se hizo el envio de ningun archivo";
		if ($v == 'v') { echo "No hay archivos pendientes de envio. No se hizo el envio de ningun archivo" . PHP_EOL; }
	}
	$a_result['a_files_all_responses'] = $a_files_all_responses;
	return $a_result;
}

//   /opt/remi/php56/root/bin/php archivos-send-exec.php

?>