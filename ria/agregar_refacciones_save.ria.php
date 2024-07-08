<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/archivospdf/solicitud.php");

session_start();

// recupera datos
// header
$id_user = SessGetUserId();

$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_producto =  (isset($_REQUEST['Id_producto'])) ? $_REQUEST['Id_producto'] : '';
$id_solicitud =  (isset($_REQUEST['IdSolicitud'])) ? $_REQUEST['IdSolicitud'] : '';
$id_partida =  (isset($_REQUEST['id_partida'])) ? $_REQUEST['id_partida'] : '';
$id_categoria = (isset($_REQUEST['IdCategoria_fk'])) ? $_REQUEST['IdCategoria_fk'] : '';
$data_json = (isset($_REQUEST['data_json'])) ? $_REQUEST['data_json'] : '';
$fecha_show = (isset($_REQUEST['fecha'])) ? $_REQUEST['fecha'] : "";

$is_show = 1;
$msg = '';
$result = 0;

if ($op == 'ShowImg') {
	if (!strlen($id_producto)) {
		$msg = 'Hubo un error, No se pudo cargar la imagen';
		$result = -1;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		$qry = "SELECT img FROM productos WHERE Idproducto = $id_producto";
		$a_producto = DbQryToRow($qry);	

		$a_producto['result'] = 1;
		$a_producto['msg'] = $msg;
		$a_ret = $a_producto;
	
		echo json_encode($a_ret);
		exit();
	}
/* agrega productos por categorias */
} else if ($op == 'SaveData') {
    
    if (!strlen($id_categoria)) {
        $msg = 'Erro no a seleccionado ninguna categoria';
        $result = -1;
    } 

    $qry = "SELECT MAX(IdSolicitud) FROM solicitudes";
    $id_solicitud  =  DbGetFirstFieldValue($qry);


    $qry = "SELECT IdCategoria_fk FROM solicitudes WHERE IdSolicitud = $id_solicitud AND IdCategoria_fk LIKE '%$id_categoria%'";
    $resp  =  DbGetFirstFieldValue($qry);

    $a_categorias = explode(',', $resp); 

    /* evita que la categoria se duplique */
    if (in_array($id_categoria, $a_categorias)) { /* tiene categoria  */
        $datos = json_decode($data_json, true);   
        $qry = "SELECT MAX(IdPartida) FROM productos_solicitud WHERE IdSolicitud = $id_solicitud";
        $valor =  DbGetFirstFieldValue($qry);
        if (!empty($datos)) {
            if (!$valor) {
                $i = 0;
            }
                $i = $valor;
            foreach ($datos as $id => $cantidad) {
                $i = $i + 1;
                $qry = "INSERT INTO productos_solicitud (IdPartida, IdProducto_fk, Cantidad, IdSolicitud) VALUES ($i, $id, $cantidad, $id_solicitud)";
                $res_upd = DbExecute($qry);
                DbCommit();
                if (is_string($res_upd)) {
                    $msg = 'Error al  agregar los productos:' . $res_upd;
                    $result = -1;
                } else {
                    if (!$res_upd) {
                        $msg = 'Error al  agregar los productos';
                        $result = -1;
                    } else {
                        $msg = 'Productos agregados';
                        $result = 1;
                    }
                }        
            }
        } else {
            $msg = 'Ups no has selecciona ningun producto';
            $result = -1;
        }
        
    } else {

        /* si no tiene categorias la agrega  */
        $a_categorias = array();
        $qry = "SELECT IdCategoria_fk FROM solicitudes WHERE IdSolicitud = $id_solicitud";   
        $valores  =  DbGetFirstFieldValue($qry);
        if (isset($valores)) {            
            $s_categoria = explode(',', $valores);                     
        }        
        
        $a_data = count($s_categoria);

        if (!in_array($id_categoria, $s_categoria)) {    
            $rollos = 17;
            $pinturas = 22;
            if (in_array($rollos, $s_categoria) || in_array($pinturas, $s_categoria)) {
                $msg = 'Las categorias Rollos Termicos y Pinturas no se puden mezclar con las demas categorias';
                $result = -1;
                $a_ret = array('result' => $result, 'msg' => $msg);
                echo json_encode($a_ret);
                exit();   
            } else {
                if ($id_categoria == '17') {                                                  
                    if ($a_data > 0) {                                                                                            
                        $msg = 'No se puede agregar rollos a la solcilitud';
                        $result = -1;
                        $a_ret = array('result' => $result, 'msg' => $msg);
                        echo json_encode($a_ret);
                        exit();                               
                    }
                }
                if ($id_categoria == '22') {                                        
                    if ($a_data > 0) {                                          
                        $msg = 'No se puede agregar pinturas a la solcilitud';
                        $result = -1;
                        $a_ret = array('result' => $result, 'msg' => $msg);
                        echo json_encode($a_ret);
                        exit();   
                    }
                }
            }
        }    
        
               
        array_push($a_categorias, $valores, $id_categoria);
        $cadena = implode(',', $a_categorias);   
        $cadena = ltrim($cadena, ',');
        $qry = "UPDATE solicitudes SET IdCategoria_fk = '$cadena' WHERE IdSolicitud = $id_solicitud";                
        $res_upd = DbExecute($qry);
        DbCommit();
        if (is_string($res_upd)) {
            $msg = 'Error al agregar la categoria:' . $res_upd;
            $result = -1;
        } else {
            if (!$res_upd) {
                $msg = 'Error al agregar la categoria';
                $result = -1;
            } else {
                $datos = json_decode($data_json, true);
                $qry = "SELECT MAX(IdPartida) FROM productos_solicitud WHERE IdSolicitud = $id_solicitud";
                $valor =  DbGetFirstFieldValue($qry);
                if (!empty($datos)) {
                    if (!$valor) {
                        $i = 0;
                    }
                        $i = $valor;
                    foreach ($datos as $id => $cantidad) {
                        $i = $i + 1;
                        $qry = "INSERT INTO productos_solicitud (IdPartida, IdProducto_fk, Cantidad, IdSolicitud) VALUES ($i, $id, $cantidad, $id_solicitud)";
                        $res_upd = DbExecute($qry);
                        DbCommit();
                        if (is_string($res_upd)) {
                            $msg = 'Error al agregar los productos:' . $res_upd;
                            $result = -1;
                        } else {
                            if (!$res_upd) {
                                $msg = 'Error al agregar los productos';
                                $result = -1;
                            } else {
                                $msg = 'Productos agregados.';
                                $result = 1;
                            }
                        }        
                    }
                } else {
                    $msg = 'Ups no has selecciona ningun producto';
                    $result = -1;
                }                    
            }
        } 
    }
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();

} else if ($op == 'ShowProducts') {
    $qry = "SELECT t2.Id, t3.Referencia, t3.NombreRefaccion, t2.Cantidad, t2.IdPartida AS icons
            FROM solicitudes t1
            LEFT JOIN productos_solicitud t2 ON t1.IdSolicitud = t2.IdSolicitud
            LEFT JOIN productos t3 ON t2.IdProducto_fk = t3.IdProducto
            WHERE t1.IdSolicitud = $id_solicitud ORDER BY t2.Id ASC";
    $a_producto = DbQryToArray($qry, true);	
    
    $a_data = array();

    foreach ($a_producto as $row){
        $a_data_line = array();
    
        $id = $a_data_line['Id'] = (string) $row['Id'];
        // $id_partida = $a_data_line['IdPartida'] = (string) $row['IdPartida'];
        $referencia =  $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
        $refaccion =  $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
        $cantidad =  $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
        $icons =  $a_data_line['icons'] = (string) $row['icons'];

        if (!strlen($id)) {
            $a_data_line[] = '';
        } else if (!strlen($referencia)) {
            $a_data[] =  $a_data;
        } else if (!strlen($refaccion)) {
            $a_data[] =  $a_data;
        } else if (!strlen($cantidad)) {
            $a_data[] =  $a_data;
        } else if (!strlen($icons)) {
            $a_data[] =  $a_data;
        // } else if (!strlen($id)){
        //     $a_data[] =  $a_data;
        } else {

        $a_data_line['Id'] = (string) $row['Id'];
        // $a_data_line['IdPartida'] = (string) $row['IdPartida'];
        $a_data_line['Referencia'] =  (string) utf8_encode($row['Referencia']);
        $a_data_line['NombreRefaccion'] = (string) utf8_encode($row['NombreRefaccion']);
        $a_data_line['Cantidad'] = (string)  utf8_encode($row['Cantidad']);
        // $a_data_line['icons'] = (string) $row['icons'];

        $icons =  "<button type='button' class='btn btn-danger btn-sm btn-delete-product' data-id='$id'><i class='fas fa-trash-alt'></i></button>";
        $a_data_line['icons'] = $icons;

        $a_data[] = $a_data_line; 
        }        
    }

    $json =  json_encode(array('data' => $a_data), true);
    echo $json;	
    

} else if ($op == 'Cancelar') {
    $qry = "SELECT * FROM solicitudes ORDER BY IdSolicitud DESC LIMIT 1";
    $id_solicitud  =  DbGetFirstFieldValue($qry);

    $qry = "DELETE FROM productos_solicitud WHERE IdSolicitud = $id_solicitud";
    $res_upd = DbExecute($qry);
    if (is_string($res_upd)) {
        $msg = 'Error al cancelar la solicitud:' . $res_upd;
        $result = -1;
    } else {
        if (!$res_upd) {
            $msg = 'Error al cancelar la solicitud';
            $result = -1;
        } else {
            $qry = "DELETE FROM solicitudes WHERE IdSolicitud = $id_solicitud";
            $res_upd = DbExecute($qry);
            if (is_string($res_upd)) {
                $msg = 'Error al cancelar la solicitud:' . $res_upd;
                $result = -1;
            } else {
                if (!$res_upd) {
                    $msg = 'Error al cancelar la solicitud';
                    $result = -1;
                } else {
                    $msg = 'Solicitud cancelada con exito';
                    $result = 1;
                }
            }
        }
    }   
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();
} else if ($op == 'Delete_product') {

    if (!strlen($id_partida)) {
        $msg = 'Error al seleccionar el producto';
        $result = -1;
    }

    $qry = "DELETE FROM productos_solicitud WHERE Id = $id_partida";
    $res_upd = DbExecute($qry);

    if (is_string($res_upd)) {
        $msg = 'Error al eliminar el producto:' . $res_upd;
        $result = -1;
    } else {
        if (!$res_upd) {
            $msg = 'Error al eliminar el producto';
            $result = -1;
        } else {
            $msg = 'Productos eliminado con exito';
            $result = 1;
            $qry = "SELECT MAX(IdSolicitud) FROM solicitudes";
            $id_solicitud = DbGetFirstFieldValue($qry);

            $qry = "SELECT t2.IdCategoria_fk 
                    FROM productos_solicitud t1
                    LEFT JOIN productos t2 ON t1.IdProducto_fk = t2.IdProducto
                    LEFT JOIN productos_categorias t3 ON t2.IdCategoria_fk = t3.IdCategoria
                    WHERE IdSolicitud = $id_solicitud GROUP BY t2.IdCategoria_fk";
            $a_categoria = DbQryToArray($qry);
            
            $a_categoria_update = '';
            foreach ($a_categoria as $row) {
                $a_categoria_update .= $row['IdCategoria_fk'].",";
            }

            $a_data_update = str_replace(','," ", $a_categoria_update);
            $a_data_update = explode(" ", $a_data_update);            
            $a_data_update = implode(",", $a_data_update);
            $qry = "UPDATE solicitudes SET IdCategoria_fk = '$a_data_update' WHERE IdSolicitud = $id_solicitud"; 
            $res_upd = DbExecute($qry);
                        
            $qry = "SELECT * FROM productos_solicitud WHERE IdSolicitud = $id_solicitud";
            $data = DbGetFirstFieldValue($qry);
            if (!$data) {
                $qry = "UPDATE solicitudes SET IdCategoria_fk = NULL WHERE IdSolicitud = $id_solicitud";                
                $res_upd = DbExecute($qry);  
                $result = 1;                                 
            } 
        }
    }   
    $a_ret = array('result' => $result, 'msg' => $msg);
    echo json_encode($a_ret);
    exit();
    
} else if ($op == 'Revision') {

    $qry = "SELECT MAX(IdSolicitud) FROM solicitudes";
    $id_solicitud  =  DbGetFirstFieldValue($qry);

    $qry  = "UPDATE solicitudes SET Estatus = 2 WHERE IdSolicitud = $id_solicitud";
    $res_upd = DbExecute($qry);
    if (is_string($res_upd)) {
        $msg = 'Error al enviar la solicitud:' . $res_upd;
        $result = -1;
        $a_ret = array('result' => $result, 'msg' => $msg);
        echo json_encode($a_ret);
    } else {
        if (!$res_upd) {
            $msg = 'Error al enviar la solicitud';
            $result = -1;
            $a_ret = array('result' => $result, 'msg' => $msg);
            echo json_encode($a_ret);
        } else {
            
            generaVentaPdf($id_solicitud, $is_show, $fecha_show);

            $directorio = '../pdf_downloads/'; 
            $archivos = scandir($directorio, SCANDIR_SORT_DESCENDING); // Obtener la lista de archivos en la carpeta				
            $ultimo_archivo = reset($archivos); // Obtener el último archivo descargado
            
            if (strlen($ultimo_archivo)) {

                $qry = "SELECT Folio, IdEstacion_fk, IdUsuario_fk, IdCategoria_fk
                        FROM solicitudes
                        WHERE IdSolicitud = $id_solicitud";
                $a_data = DbQryToRow($qry);
                $folio = $a_data['Folio'];
                $id_usuario = $a_data['IdUsuario_fk'];
                $id_estacion = $a_data['IdEstacion_fk'];		
                $id_categoria = $a_data['IdCategoria_fk'];
                
                /* obtiene el email del gerente */
                $qry = "SELECT Email FROM seg_usuarios WHERE IdUsuario = $id_usuario";						
                $a_usuarios = DbQryToRow($qry);
                $email = $a_usuarios['Email'];

                /* obtiene el email del supervisor */
                $qry = "SELECT t1.EMail
                FROM seg_usuarios t1 
                LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUsuario_fk
                WHERE t2.IdEstacion_fk = $id_estacion";
                $email_supervisor = DbGetFirstFieldValue($qry);

                $qry = "SELECT EstacionServicio, NoEstacion FROM estaciones WHERE IdEstacion = $id_estacion";						
                $a_estaciones = DbQryToRow($qry);
                $estacion = $a_estaciones['EstacionServicio'];
                $no_estacion = $a_estaciones['NoEstacion'];

                $id_categoria = ltrim($id_categoria, ',');						
                
                $a_data_categoria = array();
                $qry = "SELECT Categoria FROM productos_categorias WHERE IdCategoria IN($id_categoria)";													
                $a_categoria =  DbQryToArray($qry);
                foreach ($a_categoria as $row){
                    $valor =  $row['Categoria'];
                    array_push($a_data_categoria, $valor);
                }
                $categorias = implode(', ', $a_data_categoria);

                $mail_to = $email_supervisor; // 
                $files[] = '../pdf_downloads/'.$ultimo_archivo;
                $mail_from = $email; //responder a 
                $mail_from_name = 'CORPOGAS'; //nombre de la empresa
                $mail_subject = 'Nueva solicitud categoria '. $categorias.'  No.Estacion '.$no_estacion;
                $mensaje_de_alerta = '';
                $mail_text_body =  'Nueva solicitud de refacciones para la estación '.$no_estacion.'  '.$estacion.' - Folio - '.$folio;
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

                        $msg = "Ups no hemos podido enviar el correo.";
                        $result = - 1;
                        $datos['msg'] = $msg;
                        $datos['result'] = $result;
                        $a_ret = $datos;
                        echo json_encode($a_ret);
                        exit();

                    } else {	
                        $ruta = $directorio.''.$ultimo_archivo;
                        if (file_exists($ruta)) { // verifica que exista el archivo
                            if (unlink($ruta)) { // Intentar borrar el archivo
                                $msg = "Solicitud enviado a revsion";
                                $result = 1;
                                $datos['msg'] = $msg;
                                $datos['result'] = $result;
                                $a_ret = $datos;
                                echo json_encode($a_ret);
                                exit();
                            } else {                            
                                //si no se pudiera el borrar el pdf entra aqui;                         
                                unlink($ruta);
                            }		                    
                        }
                    }                
                } else {        

                    $msg = "Ups no pudimos enviar la solciitud a revision intente nuevamente";
                    $result = -1;
                    $datos['msg'] = $msg;
                    $datos['result'] = $result;
                    $a_ret = $datos;
                    echo json_encode($a_ret);
                    exit();	                
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

// $a_ret = array('result' => $result, 'msg' => $msg);
// echo json_encode($a_ret);
?>