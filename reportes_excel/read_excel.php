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


$rutaArchivo = (isset($_FILES['excel'])) ? $_FILES['excel'] : '';
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

if ($op == 'upload') {

    if (!strlen($rutaArchivo['name'])) {
        // $a_ret = ['msg' => 'Por favor sube un archivo', 'result' => -1];
        // echo json_encode($a_ret);
        // exit();
    }

    $archivoTmp = $rutaArchivo['tmp_name'];
    $file_name =  $rutaArchivo['name'];
    $directorio = '../archivospdf/';
    $ruta_final = $directorio . $file_name;

    if (move_uploaded_file($archivoTmp, $ruta_final)) {

        $file_end = $ruta_final;

        $objPHPExcel = PHPExcel_IOFactory::load($file_end);
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn(); // letra columna
        $highestCol = PHPExcel_Cell::columnIndexFromString($highestColumn); // numero columna

        $col_b = 'B'; // no_estacion
        $col_c = 'C'; // Nombre corto estacion
        $col_e = 'E'; // nombre
        $col_f = 'F'; // correo
        $col_h = 'H'; // razon social/nombre estacion
        $col_o = 'O'; //supervisor nombre
        $col_p = 'P'; //correo del supervisor
        $col_g = 'G'; //cel Estacion

        $a_gerentes = [];
        $a_supervisores = [];
        $a_estaciones = [];

        for ($row = 1; $row <= $highestRow; $row++){
            $col = 0;
            $num_estacion = '';
            $correo_gerente = '';
            $nombre_corto_es = '';
            $nombre = '';
            $apellido_p = '';
            $apellido_m = '';

            $email_supervisor = '';
            $nombre_s = '';
            $apellido_ps = '';
            $apellido_ms = '';
            $razonSocial = '';
            $tel_estacion = '';

            for ($col = 0; $col <= $highestCol; $col++) {
                $colName = PHPExcel_Cell::stringFromColumnIndex($col);
                $valor = $sheet->getCell($colName . $row)->getValue();
                // echo "Row: $row, Col: $colName, Col Num: $col, valor: $valor" . "<br>";
                if ($valor === 'E.S' || $valor === 'Gerente' || $valor === 'Correo de GPV' || $valor == 'Supervisor de Op' || $valor == 'Correo del Supervisor' || $valor == 'Cel. Estacion' || $valor == 'Razón Social') {
                    continue 2;
                }
                /* num estacion */
                if ($colName == $col_b && strlen($valor)) {
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
                /* nombre de los usuarios */
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
                /* nombre corto estacion */
                if ($colName == $col_c) {
                    $nombre_corto_es = $valor;
                }
                /* SUPERVISORES */
                /* nombres supervisor */
                if ($colName == $col_o && strlen($valor)) {
                    $a_supervisor = explode(' ', $valor);
                    $size =  count($a_supervisor);

                    if ($size == 1) {                    
                        
                        $nombre_s = $a_supervisor[0];
                        $apellido_ps = '';
                        $apellido_ms = '';

                    } else if ($size == 2) {
                        
                        $nombre_s = $a_supervisor[0];
                        $apellido_ps = $a_supervisor[1];
                        $apellido_ms = '';

                    } else if ($size == 3) {
                        
                        $nombre_s = $a_supervisor[0];
                        $apellido_ps = $a_supervisor[1];
                        $apellido_ms = $a_supervisor[2];

                    } else if ($size == 4) {
                        
                        $nombre_s = $a_supervisor[0];
                        $apellido_ps = $a_supervisor[1];
                        $apellido_ms = $a_supervisor[2];

                    } else if ($size == 5) {
                        
                        $nombre_s = $a_supervisor[0]." ".$a_supervisor[1];
                        $apellido_ps = $a_supervisor[2]." ".$a_supervisor[3];
                        $apellido_m = $a_supervisor[4];

                    } else if ($size == 6) {

                        $nombre_s = $a_supervisor[0]." ".$a_supervisor[1];
                        $apellido_ps = $a_supervisor[2]." ".$a_supervisor[3];
                        $apellido_ms = $a_supervisor[4]." ".$a_supervisor[5];

                    }
                }

                /* email supervisor */
                if ($colName == $col_p && strlen($valor)) {
                    $email_supervisor = $valor;
                }

                /* razon social */
                if ($colName == $col_h && strlen($valor)) {
                    $razonSocial = $valor;
                }

                /* telefono estacion */
                if ($colName == $col_g && strlen($valor)) {
                    $tel_estacion = $valor;                        
                }
            }        

            if ($num_estacion || $nombre || $correo_gerente) {
                $a_gerentes[] = ['ES' => $num_estacion, 'Nombre' => $nombre, 'ApellidoP' => $apellido_p, 'ApellidoM' => $apellido_m, 'email' => $correo_gerente, 'NombreCorto' => $nombre_corto_es];
            }

            if ($num_estacion || $email_supervisor || $nombre_s) {
                $a_supervisores[] = ['ES' => $num_estacion, 'Nombre' => $nombre_s, 'ApellidoP' => $apellido_ps, 'ApellidoM' => $apellido_ms, 'email' => $email_supervisor];
            }

            if ($num_estacion || $email_supervisor || $razonSocial) {
                $a_estaciones[] = ['ES' => $num_estacion, 'RazonSocial' => $razonSocial, 'email' => $email_supervisor, 'tel' => $tel_estacion, 'NombreCorto' => $nombre_corto_es];
            }
        }

        $msg = '';
        $result = '';
        $response_user = [];

        foreach($a_gerentes as $item) {
            

            $no_estacion =  $item['ES'];
            $name = $item['Nombre']." ".$item['ApellidoP']." ".$item['ApellidoM'];
            $email =  $item['email'];

            $nombref =  $item['Nombre'];
            $apellidoP =  $item['ApellidoP'];
            $apellidoM = $item['ApellidoM'];
            $nombrecorto = $item['NombreCorto'];        
            
            $qry = "SELECT t1.IdUsuario, t3.IdEstacion, t3.NoEstacion, t1.Nombre, t1.ApellidoPaterno, t1.ApellidoMaterno
                    FROM seg_usuarios t1 
                    LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
                    LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
                    WHERE t1.UsuarioPerfilId_fk = 13 AND t3.NoEstacion = $no_estacion
                    AND (CONCAT(t1.Nombre,' ', t1.ApellidoPaterno,' ',t1.ApellidoMaterno) LIKE '%$name%' 
                    OR t1.Email = '$email')";
            $a_qry = DbQryToRow($qry);

            if (empty($a_qry)) {            
                $qry = "SELECT t1.IdUsuario
                        FROM seg_usuarios t1 
                        LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
                        LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
                        WHERE CONCAT(t1.Nombre,' ', t1.ApellidoPaterno,' ',t1.ApellidoMaterno) LIKE '%$name%' OR t1.Email = '$email'";
                $a_user = DbQryToRow($qry);
                $id_user = $a_user['IdUsuario'];

                if (strlen($id_user)) {

                    if (!strlen($apellidoP)) {
                        $apellidoP = '';
                    }

                    if (!strlen($apellidoM)) {
                        $apelliidoM = '';
                    }

                    if (!strlen($email)) {
                        $email = '';
                    }

                    $nombref = utf8_encode($nombref);
                    $apellidoP = utf8_encode($apellidoP);
                    $apellidoM = utf8_encode($apellidoM);
                    
                    $qry_update = "UPDATE seg_usuarios SET Nombre = '$nombref', ApellidoPaterno = '$apellidoP', ApellidoMaterno = '$apellidoM', Email = '$email' WHERE IdUsuario = $id_user";
                    $res_update = DbExecute($qry_update);
                    DbCommit();

                    if (is_string($res_update)) {
                        $msg = 'Error al actualizar los datos:' . $res_update;
                        $result = -1;
                    } else {
                        if (!$res_update) {
                            $msg = 'Error al actualizar los datos';
                            $result = -1;
                        } else {
                            $msg = 'ok update';
                            $result = 1;
                        }
                    }
                } else {

                    $username = 'ES'.$no_estacion;
                    $nombre_corto = str_replace(' ', '', $nombrecorto);
                    $passwd = $no_estacion.''.$nombre_corto;
                    $pass = crypt($passwd, "doxasystems");

                    if (!strlen($apellidoP)) {
                        $apellidoP = '';
                    }

                    if (!strlen($apellidoM)) {
                        $apellidoM = '';
                    }

                    if (!strlen($email)) {
                        $email = '';
                    }

                    $nombref = utf8_encode($nombref);
                    $apellidoP = utf8_encode($apellidoP);
                    $apellidoM = utf8_encode($apellidoM);
                    
                    $qry_insert = "INSERT INTO seg_usuarios (UserName, Nombre, ApellidoPaterno, ApellidoMaterno, passwd, UsuarioPerfilId_fk, EsActivo, Email)
                        VALUES ('$username', '$nombref', '$apellidoP', '$apellidoM', '$pass', '13', '1', '$email')";                
                    $res_insert = DbExecute($qry_insert);
                    DbCommit();
                    if (is_string($res_insert)) {
                        $msg = 'Error al insertar los datos:' . $res_insert;
                        $result = -1;
                    } else {
                        if (!$res_insert) {
                            $msg = 'Error al insertar los datos';
                            $result = -1;
                        } else {
                            $msg = 'ok insert';
                            $result = 1;
                        }
                    }
                }
            } else {
                $msg = "no hay cambios para los usuarios";
                $result = 2;
            }
            
            $response_user[] = [
                'ES' => $no_estacion,
                'Nombre' => $name,
                'Email' => $email,
                'result' => $result,
                'msg' => $msg,
            ];
        }

        $msg_s = '';
        $result_s = '';
        $response_super = [];

        /* SUPERVISORES */
        foreach($a_supervisores as $item) {

            $no_es = $item['ES'];
            $nombre_super = $item['Nombre'].' '.$item['ApellidoP'].' '.$item['ApellidoM'];
            $nombreS = $item['Nombre'];
            $apellidopS = $item['ApellidoP'];
            $apellidomS = $item['ApellidoM'];
            $emailS = $item['email'];

            $qry = "SELECT t1.IdUsuario, t3.IdEstacion, t3.NoEstacion, t1.Nombre, t1.ApellidoPaterno, t1.ApellidoMaterno
                    FROM seg_usuarios t1 
                    LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
                    LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
                    WHERE t1.UsuarioPerfilId_fk = 16 AND t3.NoEstacion = $no_es
                    AND (CONCAT(t1.Nombre,' ', t1.ApellidoPaterno,' ',t1.ApellidoMaterno) LIKE '%$nombre_super%' 
                    OR t1.Email = '$emailS')";
            $a_qry = DbQryToRow($qry);

            if (empty($a_qry)) {         
                $qry = "SELECT t1.IdUsuario, t1.Nombre
                        FROM seg_usuarios t1 
                        LEFT JOIN seg_estacionesusuario t2 ON t1.IdUsuario = t2.IdUSuario_fk
                        LEFT JOIN estaciones t3 ON t2.IdEstacion_fk = t3.IdEstacion
                        WHERE CONCAT(t1.Nombre,' ', t1.ApellidoPaterno,' ',t1.ApellidoMaterno) LIKE '%$nombre_super%' OR t1.Email = '$emailS'";
                $a_user = DbQryToRow($qry);
                $id_user = $a_user['IdUsuario'];

                if(strlen($id_user)) {

                    if (!strlen($apellidopS)) {
                        $apellidopS = '';
                    } 

                    if (!strlen($apellidomS)) {
                        $apellidomS = '';
                    }

                    if (!strlen($emailS)) {
                        $emailS = '';
                    }

                    $nombreS = utf8_encode($nombreS);
                    $apellidopS = utf8_encode($apellidopS);
                    $apellidomS = utf8_encode($apellidomS);
                    
                    $qry_update = "UPDATE seg_usuarios SET Nombre = '$nombreS', ApellidoPaterno = '$apellidopS', ApellidoMaterno = '$apellidomS', Email = '$emailS' WHERE IdUsuario = $id_user";
                    $res_update = DbExecute($qry_update);                
                    DbCommit();
                    if (is_string($res_update)) {
                        $msg_s = 'Error al actualizar los datos supervisor:' . $res_update;
                        $result_s = -1;
                    } else {
                        if (!$res_update) {
                            $msg_s = 'Error al actualizar los datos supervisor';
                            $result_s = -1;
                        } else {
                            $msg_s = 'ok update supervisor';
                            $result_s = 1;
                        }
                    }

                } else {

                    $username = str_replace(' ', '', $a_user['Nombre']);                
                    $passwd = 'supervisor';
                    $pass = crypt($passwd, "doxasystems");                

                    if (!strlen($apellidopS)) {
                        $apellidopS = '';
                    }

                    if (!strlen($apellidomS)) {
                        $apellidomS = '';
                    }

                    if (!strlen($emailS)) {
                        $emailS = '';
                    }

                    $nombreS = utf8_encode($nombreS);
                    $apellidopS = utf8_encode($apellidopS);
                    $apellidomS = utf8_encode($apellidomS);

                    $qry_insert = "INSERT INTO seg_usuarios (UserName, Nombre, ApellidoPaterno, ApellidoMaterno, passwd, UsuarioPerfilId_fk, EsActivo, Email)
                                    VALUES ('$username', '$nombreS', '$apellidopS', '$apellidomS', '$pass', '16', '1', '$emailS')";                
                    $res_insert = DbExecute($qry_insert);
                    DbCommit();
                    if (is_string($res_insert)) {
                        $msg_s = 'Error al insertar los datos supervisor:' . $res_insert;
                        $result_s = -1;
                    } else {
                        if (!$res_insert) {
                            $msg_s = 'Error al insertar los datos supervisor';
                            $result_s = -1;
                        } else {
                            $msg_s = 'ok insert supervisor';
                            $result_s = 1;
                        }
                    }
                }
            } else {
                $msg_s = "no hay cambios para los supervisores";
                $result_s = 2;
            }

            $response_super[] = [
                'ES' => $no_es,
                'NombreSupervisor' => $nombre_super,
                'EmailS' => $emailS,
                'result' => $result_s,
                'msg' => $msg_s,
            ];
        }

        /* Estaciones */

        $response_estacion = [];
        $msg_estacion = '';
        $result_estacion = '';

        foreach($a_estaciones as $item) {
            $numero_es = $item['ES'];        
            $nombre_corto =  $item['NombreCorto'];
            $razon_social = utf8_encode($item['RazonSocial']);
            $tel =  $item['tel'];
            $email_es = $item['email'];        

            $qry = "SELECT IdEstacion FROM estaciones WHERE NoEstacion = '$numero_es'";
            $a_qry = DbQryToRow($qry);
            
            if (empty($a_qry)) {
                $id_estacion =  $a_qry['IdEstacion'];        
                if (strlen($id_estacion)) {

                    if (!strlen($nombre_corto)) {
                        $nombre_corto = '';
                    }
                    
                    if (!strlen($razon_social)) {
                        $razon_social = '';
                    }
            
                    if (!strlen($email_es)) {
                        $email_es = '';
                    }
            
                    if (!strlen($tel)) {
                        $tel = '';
                    }

                    $qry_update = "UPDATE estaciones SET EstacionServicio = '$razon_social', NoEstacion = '$numero_es', EmailSupervisor = '$email_es', NombreCorto = '$nombre_corto', TelSupervisor = '$tel' WHERE IdEstacion = $id_estacion";
                    $res_insert = DbExecute($qry_insert);
                    DbCommit();
                    if (is_string($res_insert)) {
                        $msg_estacion = 'Error al actulizar la estacion:' . $res_insert." ".$qry_update;
                        $result_estacion = -1;
                    } else {
                        if (!$res_insert) {
                            $msg_estacion = 'Error al actualizar la estacion';
                            $result_estacion = -1;
                        } else {
                            $msg_estacion = 'ok update estacion';
                            $result_estacion = 1;
                        }
                    }
                } else {

                    if (!strlen($nombre_corto)) {
                        $nombre_corto = '';
                    }
            
                    if (!strlen($razon_social)) {
                        $razon_social = '';
                    }
            
                    if (!strlen($email_es)) {
                        $email_es = '';
                    }
            
                    if (!strlen($tel)) {
                        $tel = '';
                    }

                    $qry_insert = "INSERT INTO estaciones (EstacionServicio, NoEstacion, EsActivo, EmailSupervisor, NombreCorto, TelSupervisor) 
                                    values ('$razon_social', '$numero_es', '1', '$email_es', '$nombre_corto', '$tel')";            
                    $res_insert = DbExecute($qry_insert);
                    DbCommit();
                    if (is_string($res_insert)) {
                        $msg_estacion = 'Error al insertar la estacion:' . $res_insert." ".$qry_insert;
                        $result_estacion = -1;
                    } else {
                        if (!$res_insert) {
                            $msg_estacion = 'Error al insertar la estacion';
                            $result_estacion = -1;
                        } else {
                            $msg_estacion = 'ok insert estacion';
                            $result_estacion = 1;
                        }
                    }
                }
            } else {
                $msg_estacion = "no hay cambios para las estaciones";   
                $result_estacion = 2;
            }

            $response_estacion[] = [
                'ES' => $numero_es,
                'RazonSocial' => $razon_social,            
                'result' => $result_estacion,
                'msg' => $msg_estacion,
            ];        
        }


        $a_ret = [];
        if (unlink($file_end)) {
            $a_ret = ['msg' => 'El archivo ha sido eliminado exitosamente.', 'result' => 1];
        } else {
            $a_ret = ['msg' => 'Error al intentar eliminar el archivo.', 'result' => 1];
        }

        $response = [
            'Users' => $response_user,
            'Supervisores' => $response_super,
            'Estaciones' => $response_estacion,
            'file_resp' => $a_ret,
        ];
        

        echo json_encode($response);
        

    } else {

        $a_ret = ['msg' => 'Error al cargar el archivo.', 'result' => -1];
        echo  json_encode($a_ret);
        exit();

    }
}
// }
// Ruta del archivo Excel
// $rutaArchivo = 'BASE ESTACIONES 20240829 Portal de Refacciones.xlsx';

// Llamar a la función para leer y mostrar el contenido del archivo Excel
// $resp = leerExcel($rutaArchivo);
// echo $resp."<br>";