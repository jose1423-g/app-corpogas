<?php

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
$id_solicitud = (isset($_REQUEST['id_solicitud'])) ? $_REQUEST['id_solicitud'] : "";
$fecha_show = (isset($_REQUEST['fecha'])) ? $_REQUEST['fecha'] : "";



$msg = "";
$result = 0;
$is_show = 1;

$fecha_hoy = "";
$fecha_hoy =  DtDbToday($fecha_hoy);

// $minutos = date('i');

if ($op == 'loadSolicitud') {
	if (!strlen($id_solicitud)) {
		$msg = "Ups hubo un error al cargar la solicitud";
		$result = -1;
	} elseif (!strlen($id_user)){
		$msg = "Su session ha expirado";
		$result = -1;
	} else {
		$qry = "SELECT t1.Folio, t1.Estatus, t1.Estatus AS btn_status, t1.Fecha, t1.MatEntregado, t2.Nombre AS AreaSolicita, t1.EntregoMatCompleto, t1.FolioRemision, t1.Observaciones,
		t1.MotRechazo, t1.ObGenerales, t5.NoEstacion, CONCAT(t3.Nombre,' ',t3.ApellidoPaterno,' ',t3.ApellidoMaterno) AS Gerente, t3.Email, t3.Telefono
		FROM solicitudes t1 
		LEFT JOIN areas t2 ON t1.IdAreaSolicita_fk = t2.IdArea
		LEFT JOIN seg_usuarios t3 ON t1.IdUsuario_fk = t3.IdUsuario
		LEFT JOIN seg_estacionesusuario t4 ON t3.IdUsuario = t4.IdUsuario_fk 
		LEFT JOIN estaciones t5 ON t4.IdEstacion_fk = t5.IdEstacion
		WHERE t1.IdSolicitud  = $id_solicitud";
		
        $a_datos = DbQryToRow($qry);	
	
		$estatus = $a_datos['Estatus'];
		if ($estatus == 2) {
			$estatus = "<span class='text-warning'>Pendiente Revision</span>";
			$a_datos['Estatus'] = $estatus;
		} else if ($estatus == 3) {
			$estatus = "<span class='text-danger'>Pendiente Revision</span>";
			$a_datos['Estatus'] = $estatus;
		} else if ($estatus == 4) {
			$estatus = "<span class='text-success'>Aceptada</span>";
			$a_datos['Estatus'] = $estatus;
		}

		$fecha =  DtDbToShow($a_datos['Fecha']);
		$a_datos['Fecha'] = $fecha;
		$observaciones = utf8_encode($a_datos['Observaciones']);
		$a_datos['Observaciones'] = $observaciones;
		$mot_rechazo = utf8_encode($a_datos['MotRechazo']);
		$a_datos['MotRechazo'] = $mot_rechazo;
		$obs_generales = utf8_encode($a_datos['ObGenerales']);
		$a_datos['ObGenerales'] = $obs_generales;
		$Gerente = utf8_encode($a_datos['Gerente']);
		$a_datos['Gerente'] = $Gerente;
		
		$result = 1;
		$a_datos['result'] = $result;
		$a_datos['msg'] = $msg;
		$a_ret = $a_datos; 
		echo json_encode($a_ret);
	}
	

} else if ($op == 'ShowProducts') {

	$qry = "SELECT t2.IdPartida, t3.Referencia, t3.NombreRefaccion, t2.Cantidad
	FROM solicitudes t1
	LEFT JOIN productos_solicitud t2 ON t1.IdSolicitud = t2.IdSolicitud
	LEFT JOIN productos t3 ON t2.IdProducto_fk = t3.IdProducto
	WHERE t1.IdSolicitud = $id_solicitud ORDER BY t2.IdPartida ASC";

	$a_producto = DbQryToArray($qry);

	$a_data = array();

	foreach ($a_producto as $row){
		$a_data_line = array();

		$id_partida = $id = $a_data_line['IdPartida'] = (string) $row['IdPartida'];
		$referencia =  $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
		$refaccion =  $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
		$cantidad =  $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
		// $icons =  $a_data_line['icons'] = (string) $row['icons'];
		if (!strlen($id_partida)) {
			$a_data_line[] = '';
		} else if (!strlen($referencia)) {
			$a_data[] =  $a_data;
		} else if (!strlen($refaccion)) {
			$a_data[] =  $a_data;
		} else if (!strlen($cantidad)) {
			$a_data[] =  $a_data;
		// } else if (!strlen($icons)) {
		// 	$a_data[] =  $a_data;
		} else {

		$a_data_line['IdPartida'] = (string) $row['IdPartida'];
		$a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
		$a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
		$a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);

		$a_data[] = $a_data_line; 
		}        
	}
	$json =  json_encode(array('data' => $a_data), true);
	echo $json;	
} else if ($op == 'aprobar') {

	if (!strlen($id_solicitud)) {
		$msg = "Error al aprobar la solicitud.";
		$result = -1;
	} else if(!strlen($id_user)){
		$msg = "Su session ha expirado.";
		$result = -1;
	} else {

		$qry = "UPDATE solicitudes
				SET Estatus = 4, 
				FechaAprobacion = '$fecha_hoy'
				WHERE IdSolicitud = $id_solicitud";				
		$res_ins = DbExecute($qry, true);

		if (is_string($res_ins)) {
			$msg = 'Error al aprobar la solcitud: ' . $res_ins;
			$result = -1;
			$datos['msg'] = $msg;
			$datos['result'] = $result;
			$a_ret = $datos;
			echo json_encode($a_ret);
			exit();
		} else {
			if (!$res_ins) {
				$msg = 'Error al aprobar la solicitud';
				$result = -1;
				$datos['msg'] = $msg;
				$datos['result'] = $result;
				$a_ret = $datos;
				echo json_encode($a_ret);
				exit();
			} else { 				
				generaVentaPdf($id_solicitud, $is_show, $fecha_show);

				$directorio = '../pdf_downloads/'; 
				$archivos = scandir($directorio, SCANDIR_SORT_DESCENDING); // Obtener la lista de archivos en la carpeta				
				$ultimo_archivo = reset($archivos); // Obtener el último archivo descargado

				if (strlen($ultimo_archivo)) {

					$qry = "SELECT IdUsuario_fk FROM solicitudes WHERE IdSolicitud = $id_solicitud";					
					$id_usuario =  DbGetFirstFieldValue($qry);

					if (!strlen($id_usuario)) {

						$msg = "Ups ocurrio un error al enviar el pdf";
						$result = -1;
						$datos['msg'] = $msg;
						$datos['result'] = $result;
						$a_ret = $datos;
						echo json_encode($a_ret);
						exit();

					} else {

						$qry = "SELECT Folio FROM solicitudes WHERE IdSolicitud = $id_solicitud";						
						$folio =  DbGetFirstFieldValue($qry);
						
						$qry = "SELECT Email FROM seg_usuarios WHERE IdUsuario = $id_usuario";						
						$a_usuarios = DbQryToRow($qry);
						$email = $a_usuarios['Email'];

						$qry = "SELECT IdEstacion_fk FROM seg_estacionesusuario WHERE IdUsuario_fk = $id_usuario";						
						$id_estacion =  DbGetFirstFieldValue($qry);
					
						$qry = "SELECT EmailSupervisor FROM estaciones WHERE IdEstacion = $id_estacion";						
						$email_supervisor = DbGetFirstFieldValue($qry);

						$qry = "SELECT EstacionServicio, NoEstacion FROM estaciones WHERE IdEstacion = $id_estacion";						
						$a_estaciones = DbQryToRow($qry);
						$estacion = $a_estaciones['EstacionServicio'];
						$no_estacion = $a_estaciones['NoEstacion'];

						$qry = "SELECT Folio, IdCategoria_fk FROM solicitudes WHERE IdSolicitud = $id_solicitud";						
						$a_solicitudes = DbQryToRow($qry);
						$folio = $a_solicitudes['Folio'];
						$id_categoria = $a_solicitudes['IdCategoria_fk'];

						$id_categoria = ltrim($id_categoria, ',');						
						
						$a_data_categoria = array();
						$qry = "SELECT Categoria FROM productos_categorias WHERE IdCategoria IN($id_categoria)";													
						$a_categoria =  DbQryToArray($qry);
						foreach ($a_categoria as $row){
							$valor =  $row['Categoria'];
							array_push($a_data_categoria, $valor);
						}
						$categorias = implode(', ', $a_data_categoria);
						
						$mail_to = $email.','.$email_supervisor; // destinatarios jctg1@hotmail.com
						$files[] = '../pdf_downloads/'.$ultimo_archivo;
						$mail_from = $email_supervisor; //responder a 
						$mail_from_name = 'CORPOGAS'; //nombre de la empresa
						$mail_subject = $categorias.' '.$no_estacion;
						$mensaje_de_alerta = '';
						$mail_text_body =  'Solicitud de Refacciones Estación '.$no_estacion.'  '.$estacion.' - Folio - '.$folio ;
						$mail_html_body = "<html>
											<head>
											<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
											<title>$mail_subject</title>
											</head>
											<body>
												<p>$mail_text_body</p>
											</body>
											</html>";
						$mail_host = 'smtp.gmail.com';
						$mail_port = '587';
						$mail_username = 'doxasystems.mail@gmail.com'; //aparece al lado del nombre de la empresa Corpogas
						$mail_passwd = 'qptttahefmxcndli';
						$mail_smtp_secure = 'tls';
						$mail_firma_url = "";
						$mail_backup = 'compras@gruposynergo.com';// destinatario 
						$zp = 0;
						
						$ruta = $directorio.''.$ultimo_archivo;

						if (file_exists($ruta)) {
							$result = multi_attach_mail_new($mail_to, $files, $mail_from, $mail_from_name, $mail_subject, $mail_html_body, $mail_text_body, $mail_host, $mail_port, $mail_username, $mail_passwd, $mail_smtp_secure, $mail_firma_url, $mail_backup, $zp);							
							if ($result == 0) {
								$qry = "UPDATE solicitudes 
										SET Estatus = 2, 
										FechaAprobacion = '$fecha_hoy' 
										WHERE IdSolicitud = $id_solicitud";
								$res_ins = DbExecute($qry, true);
								DbCommit();
								$msg = "no se logro enviar el correo";
								$result = - 1;
								$datos['msg'] = $msg;
								$datos['result'] = $result;
								$a_ret = $datos;
								echo json_encode($a_ret);
								exit();
							} else {
								$ruta = $directorio.''.$ultimo_archivo;
								if (file_exists($ruta)) {//verifica que exista el archivo
									if (unlink($ruta)) {// Intentar borrar el archivo
										$msg = "Correo enviado";
										$result = 1;
										$datos['msg'] = $msg;
										$datos['result'] = $result;
										$a_ret = $datos;
										echo json_encode($a_ret);
										exit();
									} else {
										$qry = "UPDATE solicitudes 
												SET Estatus = 2 
												WHERE IdSolicitud = $id_solicitud";											
										$res_ins = DbExecute($qry, true);
										DbCommit();
										$msg = "Error el correo fue enviado sin el pdf intente nuevamente";
										$result = -1;
										$datos['msg'] = $msg;
										$datos['result'] = $result;
										$a_ret = $datos;
										echo json_encode($a_ret);
										exit();
									}
								} else {
									$msg = "Error la ruta especificada no existe";
									$result = -1;
									$datos['msg'] = $msg;
									$datos['result'] = $result;
									$a_ret = $datos;
									echo json_encode($a_ret);
									exit();
								}
								
							}
						} else {
							$qry = "UPDATE solicitudes 
									SET Estatus = 2 
									WHERE IdSolicitud = $id_solicitud";
							$res_ins = DbExecute($qry, true);							
							DbCommit();
							$msg = "Ups no pudimos enviar su correo intente nuevamente";
							$result = -1;
							$datos['msg'] = $msg;
							$datos['result'] = $result;
							$a_ret = $datos;
							echo json_encode($a_ret);
							exit();	
						}
						
					}
				} else {
					$msg = "Ups no pudimos agregar el pdf al correo intente nuevamente";
					$result = -1;
					$datos['msg'] = $msg;
					$datos['result'] = $result;
					$a_ret = $datos;
					echo json_encode($a_ret);
					exit();	
				}
			}
		}
	}
} else if ($op == 'rechazar'){
	if (!strlen($id_solicitud)) {
		$msg = "Error al aprobar la solicitud.";
		$result = -1;
	} else if(!strlen($id_user)){
		$msg = "Su session ha expirado.";
		$result = -1;
	} else {
		$estatus  = 3;
		$qry = "UPDATE solicitudes SET Estatus = $estatus, FechaAprobacion = '$fecha_show' WHERE IdSolicitud =  $id_solicitud";
		$res_ins = DbExecute($qry, true);
		DbCommit();
		if (is_string($res_ins)) {
			$msg = 'Error al rechazar la solcitud: ' . $res_ins;
			$result = -1;
			$datos['msg'] = $msg;
			$datos['result'] = $result;
			$a_ret = $datos;
			echo json_encode($a_ret);
			exit();
		} else {
			if (!$res_ins) {
				$msg = 'Error al rechazar la solicitud';
				$result = -1;
				$datos['msg'] = $msg;
				$datos['result'] = $result;
				$a_ret = $datos;
				echo json_encode($a_ret);
				exit();
			} else {
				$msg = 'Solicitud rechazada con exito';
				$result = 1;
				$datos['msg'] = $msg;
				$datos['result'] = $result;
				$a_ret = $datos;
				echo json_encode($a_ret);
				exit();
			}
		}
	}
}

?>