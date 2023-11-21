<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/php/knl/logs.inc.php");
require_once("$SYS_ROOT/php/knl/icons.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");


// guarda el log de acceso de usuarios, verificando si la session de php ya existe
function segSaveUserLog($id_user, $dirip, $php_session_id, $notas = '') {
	if (strlen($php_session_id)) {
		$qry = "SELECT IdLog FROM seg_usuarios_log WHERE PhpSessionId = '$php_session_id'";
		$id_log = DbGetFirstFieldValue($qry);
		if (!strlen($id_log)) {
			$fecha = DtDbToday();
			$hora = TmDbStamp();
			$qry = "INSERT INTO seg_usuarios_log (IdUsuario, Fecha, Hora, dirip, Notas, PhpSessionId)
					VALUES ($id_user, $fecha, '$hora', '$dirip', '$notas', '$php_session_id')";
			DbExecute($qry);		
		}
	}
}


// solicita acceso al usuario a una funcion
function segAccesoFuncion($funcion_especial, $id_user) {
    $es_acceso = false;
    if (strlen($id_user)) {
        $qry = "SELECT t1.IdFuncion
				FROM seg_funciones_usuarios t1
				LEFT JOIN seg_funciones_especiales t2 ON
					t1.IdFuncion = t2.IdFuncion
				WHERE t1.IdUsuario = $id_user AND
					t2.EsActivo = 1 AND
					t2.FuncionEspecial = '$funcion_especial'";
        $id_funcion = DbGetFirstFieldValue($qry);
        if (strlen($id_funcion) and $id_funcion > 0) {
            $es_acceso = true;
        } else {
            $es_acceso = segAccesoSesion($funcion_especial, $id_user);
        }
    }
    return $es_acceso;
}


// verifica si la sesion tiene acceso alguna funcion especial
function segAccesoSesion($funcion_especial, $id_user) {
    $ret = false;
    $seg_acceso = GetSessionValue('seg_acceso');
    $seg_funcion_especial = GetSessionValue('seg_funcion_especial');
    $seg_id_user = GetSessionValue('seg_id_user');
    if ($seg_acceso == 1) {
        if ($funcion_especial == $seg_funcion_especial) {
            $ret = true;
        }
    }
    return $ret;
}



// otorga acceso a la sesion
function segSetAccesoSesion($funcion_especial, $id_user, $auth_user = '') {
    SegDelAccesoSesion();
    $_SESSION['seg_acceso'] = 1;
    $_SESSION['seg_funcion_especial'] = $funcion_especial;
    $_SESSION['seg_id_user'] = $id_user;
}


// elimina el acceso de la sesion
function segDelAccesoSesion() {
    unset($_SESSION['seg_acceso']);
    unset($_SESSION['seg_funcion_especial']);
    unset($_SESSION['seg_id_user']);
}



// verifica que la contraseña sea valida para el usuario
function segEsPasswdOk($id_user, $passwd) {
    $ret = false;
    if (strlen($id_user)) {
        $qry = "SELECT passwd FROM seg_usuarios WHERE IdUsuario = $id_user";
        $passwd_db = DbGetFirstFieldValue($qry);
		$passwd_db = trim($passwd_db);
		$passwd = sha1(trim($passwd));
		if ($passwd == $passwd_db and strlen($passwd) and strlen($passwd_db)) {
			$ret = true;
		}
    }
    return $ret;
}

// guarda el log de autorizaciones sobre documentos / operaciones
function segSaveAuthLog($session_auth, $id_user_auth, $session_funcion_especial, $id_documento = null, $notas = '') {
	if ($session_auth == 1 and strlen($id_user_auth) and strlen($session_funcion_especial)) {
		$id_documento = (!strlen($id_documento)) ? 'NULL' : $id_documento;
		$fecha = DtDbToday();
		$hora = TmDbStamp();
		$qry = "INSERT INTO seg_auth_log (FuncionEspecial, IdUsuario, Fecha, Hora, IdDocumento, Notas)
				VALUES ('$session_funcion_especial', $id_user_auth, $fecha, '$hora', $id_documento, '$notas')";
		DbExecute($qry);
	}
}


// segun la funcion busca el valor definido para el usuario/funcion (ej, descuento maximo
function segValorUsuarioFuncion($funcion_id, $id_user) {
    $valor_usr_funcion = 0;
    if (strlen($id_user)) {
        $id_user_auth = GetSessionValue('seg_id_user');
        $id_user_auth = (strlen($id_user_auth)) ? $id_user_auth : $id_user;
        $qry = "SELECT t1.Valor
                FROM seg_usuariofunciones t1
                LEFT JOIN seg_funciones t2 ON
                    t1.FuncionId = t2.FuncionId
                WHERE t1.IdUsuario = $id_user_auth AND
                    t2.EsActivo = 1 AND
                    t1.FuncionId = $funcion_id";
        $valor_usr_funcion = DbGetFirstFieldValue($qry);
        $valor_usr_funcion = (strlen($valor_usr_funcion)) ? $valor_usr_funcion : 0;
    }
    return $valor_usr_funcion;
}

// verifica si el usuario tiene acceso a la aplicacion
function segAccesoAplicacion($id_user, $filename) {
    $acceso = 0;
    if (strlen($id_user) and strlen($filename)) {
        $qry = "SELECT t1.IdApp_fk
                FROM seg_perfilesaplicaciones t1
                LEFT JOIN seg_usuarioperfil t2 ON
                    t1.IdUsuarioPerfil_fk = t2.UsuarioPerfilId
                LEFT JOIN seg_aplicaciones t3 ON
                    t1.IdApp_fk = t3.IdApp
                LEFT JOIN seg_usuarios t4 ON
                    t1.IdUsuarioPerfil_fk = t4.UsuarioPerfilId_fk
                WHERE IdUsuario = $id_user AND
                    t3.FileName = '$filename'";
        $id_app = DbGetFirstFieldValue($qry);
        if (strlen($id_app) >= 1) {
            $acceso = 1;
        }
    }
    return $acceso;
}


// verifica si el perfil del usuario tiene acceso a la aplicacion
function segAccesoPerfilAplicacion($id_perfil, $id_app) {
    $acceso = 0;
    if (strlen($id_perfil) and strlen($id_app)) {
        $qry = "SELECT t1.IdApp
                FROM seg_perfilesaplicaciones t1
                LEFT JOIN seg_usuarioperfil t2 ON
                    t1.IdUsuarioPerfil = t2.UsuarioPerfilId
                LEFT JOIN seg_aplicaciones t3 ON
                    t1.IdApp = t3.IdApp
                WHERE t1.IdUsuarioPerfil = $id_perfil AND
                    t3.IdApp = '$id_app'";
        $id_app = DbGetFirstFieldValue($qry);
        if (strlen($id_app) >= 1) {
            $acceso = 1;
        }
    }
    return $acceso;
}


function segVerifyAuth($filename, $page_redir = 'pagina_acceso_denegado.php', $is_force_page = 0) {
	/*if ($filename != 'sys_license_update.php') {
		segDoxaVerify();
	}*/

    $id_user = SessGetUserId();
    
    if (strlen($id_user)) {
		$acceso = segAccesoAplicacion($id_user, $filename);
	} else {
		$acceso = 0;
		if ($is_force_page == 0) {
			$page_redir = "login.php";
		}
	}
    if ($acceso == 0) {
        redirect($page_redir . "?filename=$filename");
    }
}

function segCheckMantenimiento() {
	global $SYS_ROOT;
	$qry = "SELECT value FROM knl_params WHERE cd_param = 'es_pagina_en_mantenimiento'";
	$es_mant = DbGetFirstFieldValue($qry);
	$id_user = SessGetUserId();
	if ($es_mant == 1) {
		$content = file_get_contents("$SYS_ROOT/ccgen/en_mantenimiento.html");
		echo $content;
		if ($id_user != 1) {
			exit();
		}
	}
}

function segDoxaVerify($is_return_values = false) {
	$is_lic_ok = 0;
	$is_date_ok = 0;
	$qry = "SELECT Doxa, DoxaV FROM empresa WHERE EmpresaId = 1";
	$a_row = DbQryToRow($qry);
	// licence
	$text1 = "doxa_ofm_1977_0610";
	// obtiene numero de serie del hdd en windows (pendiente poner mas de un disco duro)(o mejor que pidan de nuevo la licencia si cambian de disco duro)
	$numero_serie = shell_exec("wmic DISKDRIVE GET SerialNumber");
	$numero_serie = trim($numero_serie);
	$a_numero_serie = explode("\n", $numero_serie);
	$numero_serie = trim($a_numero_serie[1]);
	$text2 = "4397_doxa" . $numero_serie;
	$text = $text1 . $text2;
	$doxa = hash("sha1", $text, false);
	if ($doxa == $a_row['Doxa']) {
		$is_lic_ok = 1;
	}
	
	// date verify
	$doxav = $a_row['DoxaV'];
	$doxav = strrev($doxav);
	if (strlen($doxav)) {
		$doxav = date('Ymd', $doxav);
		$today = DtDbToday();
		if ($today <= $doxav) {
			$is_date_ok = 1;
		}
	}
	
	if ($is_return_values) {
		$a_ret = array('is_lic_ok' => $is_lic_ok, 'is_date_ok' => $is_date_ok);
		return $a_ret;
	} else {
		if ($is_date_ok == 0 or $is_lic_ok == 0) {
			$id_user = SessGetUserId();
			$is_msg_1 = ($is_lic_ok == 0) ? 1 : 0;
			$is_msg_2 = ($is_date_ok == 0) ? 1 : 0;
			redirect("sys_license_message.php?is_msg_1=$is_msg_1&is_msg_2=$is_msg_2&id_user=$id_user");
		}
	}
}


function segDoxaDateVerify() {
	$is_date_ok = 0;
	$qry = "SELECT Doxa, DoxaV FROM empresa WHERE EmpresaId = 1";
	$a_row = DbQryToRow($qry);
	// date verify
	$doxav = $a_row['DoxaV'];
	$doxav = strrev($doxav);
	if (strlen($doxav)) {
		$doxav = date('Ymd', $doxav);
		$today = DtDbToday();
		if ($today <= $doxav) {
			$is_date_ok = 1;
		}
	}
	
	
	if ($is_date_ok == 1) {
		$mensaje = "Fecha de vencimiento " . DtDbToShow($doxav);
	} else {
		if (strlen($doxav)) {
		$mensaje = "La licencia esta vencida desde el día " . DtDbToShow($doxav);
		} else {
			$mensaje = "No hay registro de licencias activas.";
		}
	}
	
	return $mensaje;
}

function cerPassObfuscate($str_passwd) {
	$control_chars = "&doxa&";
	
	// primero verifica que no este obfuscated previamente
	$str_passwd_check = base64_decode(str_rot13($str_passwd));
	$control_chars_check = substr($str_passwd_check, strlen($control_chars) * -1);
	if ($control_chars_check == $control_chars) {
		$str_passwd_obs = $str_passwd;
	} else {
		// encripta
		$str_passwd .= $control_chars;
		$str_passwd_obs = str_rot13(base64_encode($str_passwd));
	}
	
	return $str_passwd_obs;
}

function cerPassClarify($str_passwd_obs) {
	$control_chars = "&doxa&";
	$str_passwd = base64_decode(str_rot13($str_passwd_obs));
	$str_passwd = substr($str_passwd, 0, strlen($control_chars) * -1);
	
	return $str_passwd;
}


function recordLock($id_cuenta, $id_user, $es_desbloqueable, $lock_msg) {
    $is_my_lock = recordIsMyLock($id_cuenta, $id_user);
    if ($is_my_lock != 1) {
        $fecha = DtDbLgToday();
        $qry = "INSERT INTO RecordLock (IdCuenta, FechaBloqueo, Usuario, Mensaje, EsDesbloqueable)
                VALUES ($id_cuenta, '$fecha', $id_user, '$lock_msg', $es_desbloqueable)";
        DbExecute($qry);
    }
    
}


function recordUnlock($id_cuenta, $id_user) {
    $qry = "DELETE FROM RecordLock WHERE IdCuenta = $id_cuenta";
    DbExecute($qry);
}


function recordIsLocked($id_cuenta, $id_user) {
    $cuenta_bloqueada = 0;
    if (strlen($id_cuenta) and strlen($id_user)) {
        $qry = "SELECT COUNT(IdRecordLock) FROM recordlock WHERE IdCuenta = $id_cuenta AND Usuario <> $id_user";
        $existe = DbGetFirstFieldValue($qry);
        if ($existe > 0) {
            $cuenta_bloqueada = 1;
        }
    }
    return $cuenta_bloqueada;
}

function recordIsMyLock($id_cuenta, $id_user) {
    $is_my_lock = 0;
    if (strlen($id_cuenta) and strlen($id_user)) {
        $qry = "SELECT COUNT(IdRecordLock) FROM recordlock WHERE IdCuenta = $id_cuenta AND Usuario = $id_user";
        $existe = DbGetFirstFieldValue($qry);
        if ($existe > 0) {
            $is_my_lock = 1;
        }
    }
    return $is_my_lock;
}


function recordLockInfo($id_cuenta, $id_user) {
    $qry = "SELECT t1.FechaBloqueo, t2.Nombres, t2.ApellidoPaterno, t2.ApellidoMaterno,
                t2.Usuario, t1.EsDesbloqueable, t1.Mensaje
            FROM recordlock t1
            INNER JOIN seg_usuarios t2 ON
                t1.Usuario = t2.IdUsuario
            WHERE t1.IdCuenta = $id_cuenta AND t1.Usuario <> $id_user";
    $a_row = DbQryToRow($qry);
    if (count($a_row) > 0) {
        $nombre_usuario = trim(trim($a_row['ApellidoPaterno']) . ' ' . trim($a_row['ApellidoMaterno']) . ' ' . trim($a_row['Nombres']));
        $nombre_usuario = (strlen($nombre_usuario)) ? $nombre_usuario : $a_row['Usuario'];
        $fecha_bloqueo = $a_row['FechaBloqueo'];
        $fecha_bloqueo = DtTmDbLgToShow($fecha_bloqueo);
        $es_desbloqueable = $a_row['EsDesbloqueable'];
        $mensaje = $a_row['Mensaje'];
        $a_ret = Array('nombre_usuario' => $nombre_usuario, 'fecha_bloqueo' => $fecha_bloqueo, 'es_desbloqueable' => $es_desbloqueable, 'mensaje' => $mensaje);
    } else {
        $a_ret = Array();
    }
    return $a_ret;
}


$es_mostrar_sin_acceso = 0;
function items($id_perfil, $es_perfil_admin, $id_menu, &$text, $is_deep = 0) {
	global $es_mostrar_sin_acceso;
	$cont_mostr = 0;
    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, FileName, IdMenu AS Children, COALESCE(t3.IdApp, -1) AS IdApp, t1.EsAdmin
            FROM seg_menu t1
            LEFT JOIN seg_aplicaciones t2 ON
                t1.IdApp = t2.IdApp
            LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                t1.IdApp = t3.IdApp
            WHERE IdMenuUp = $id_menu AND EsActivo = 1
            ORDER BY Orden";
    $a_items = DbQryToArray($qry, true, true);
    if ($is_deep == 0) {
        $text .= "<ul class=\"sub\">";
    } else {
        $text .= "<ul>";
    }
    foreach ($a_items AS $cd_mnu_i) {
        $cdi = $cd_mnu_i['IdMenu'];
		if ($es_mostrar_sin_acceso == 1) {
			$qry = "SELECT COUNT(*) FROM seg_menu WHERE IdMenuUp = $cdi AND EsActivo = 1";
		} else {
            $qry = "SELECT COUNT(*)
                    FROM seg_menu t1
                    LEFT JOIN seg_aplicaciones t2 ON
                        t1.IdApp = t2.IdApp
                    LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                        t1.IdApp = t3.IdApp
                    WHERE IdMenuUp = $cdi AND EsActivo = 1 AND COALESCE(t3.IdApp, -1) > 0";
		}
        $count = DbGetFirstFieldValue($qry);
		$desc_menu = GetTrans($cd_mnu_i['NombreMenu']);
        $desc_menu = htmlentities($desc_menu, ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
        if ($cd_mnu_i['EsAdmin'] != 1 or $es_perfil_admin) {
            if ($count > 0) {
                $text .= "<li><a href=\"#\" class=\"fly\">$desc_menu</a>";
                $cont_mostr = $cont_mostr + items($id_perfil, $es_perfil_admin, $cd_mnu_i['Children'], $text, 1);
                $text .= "</li>";
            } else {
				$desc_opt = GetTrans($cd_mnu_i['NombreMenu']);
                $desc_opt = htmlentities($desc_opt, ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
                $es_acceso = $cd_mnu_i['IdApp'] > 0;
                if ($es_acceso) {
                    $link = $cd_mnu_i['FileName'];
					if (substr($link, 0, 12) == '../AlvicFac/') {
						$text .= "<li><a target=\"_top\" href=\"$link\" onclick=\"document.getElementById('id_menu_selected').value = $cdi\">$desc_opt</a></li>";
					} else {
						$text .= "<li><a target=\"mainFrame\" href=\"$link\" onclick=\"document.getElementById('id_menu_selected').value = $cdi\">$desc_opt</a></li>";
					}
					$cont_mostr++;
                } else {
					if ($es_mostrar_sin_acceso == 1) {
						$link = "#";
						$text .= "<li><a style=\"color: #A3A3A3\">$desc_opt</a></li>";
						$cont_mostr++;
					}
                }
            }
        }
    }
    $text .= "</ul>";
	return $cont_mostr;
}

function itemsAll() {
    $id_user = SessGetUserID();
    $qry = "SELECT t1.UsuarioPerfilId, t2.EsAdmin
            FROM seg_usuarios t1
            LEFT JOIN seg_usuarioperfil t2 ON
                t1.UsuarioPerfilId = t2.UsuarioPerfilId
            WHERE IdUsuario = $id_user";
    $a_user = DbQryToRow($qry);
    $id_perfil = $a_user['UsuarioPerfilId'];
    $es_perfil_admin = ($a_user['EsAdmin'] == 1);

    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, IdMenu AS Children, t1.EsAdmin
            FROM seg_menu t1
            WHERE IdMenuUp IS NULL AND EsActivo = 1
            ORDER BY Orden";
    $a_main = DbQryToArray($qry, true, true);
    $text = "<ul id=\"nav\" style=\"position: relative; top: -12px\">";
    foreach ($a_main AS $mnu) {
        if ($mnu['EsAdmin'] != 1 or $es_perfil_admin) {
			$desc_menu = GetTrans($mnu['NombreMenu']);
            $desc_menu = htmlentities($desc_menu, ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
            // $text .= "<li class=\"top\"><a href=\"#\" class=\"top_link\"><span class=\"down\">$desc_menu</span></a>";
            $text_b = "<li class=\"top\"><a href=\"#\" class=\"top_link\"><span class=\"down\">$desc_menu</span></a>";
            $cont_mostr = items($id_perfil, $es_perfil_admin, $mnu['Children'], $text_b);
            // $text .= "</li>";
            $text_b .= "</li>";
            if ($cont_mostr == 0) {
                $text_b = "";
            }
            $text .= $text_b;
        }
    }

	$cd_param = "menu_mostrar_boton_cambia_cia";
	$qry = "SELECT value FROM knl_params WHERE cd_param = '$cd_param'";
	$mostrar_boton_cambia_cia = DbGetFirstFieldValue($qry);
		
	if ($mostrar_boton_cambia_cia == 1) {
		$text .= "<li class=\"top\"><a href=\"../../\" class=\"top_link\"><span>Cambiar de Compañia</span></a><li>";
	}

	$salir_label = GetTrans("Salir");
	$text .= "<li class=\"top\"><a href=\"login.php?Logout=true\" class=\"top_link\"><span>$salir_label</span></a><li>";
    $text .= "</ul>";
    return $text;
}

function itemsAllBTManual($filename = "", $caller = "") {
	if ($caller == "e") { // caller e = estacion, <vacio> = cliente
		$a_opciones = array(
			'autofactura_estacion.php' => array('../ccgen/autofactura_estacion.php', 'Inicio'),
			'vta_clientes_facturas_search_estacion.php' => array('../ccgen/vta_clientes_facturas_search_estacion.php', 'Busqueda de Facturas'),
			'vta_clientes_facturas_form_cancel.php' => array("../ccgen/vta_clientes_facturas_form_cancel.php?c=$caller", 'Solicitud de Cancelación')
			//,
			//'vta_clientes_facturas_search_prev.php' => array("../prev/vta_clientes_facturas_search_prev.php?c=$caller", 'Busqueda de Facturas Anteriores')
		);
	} else {
		$a_opciones = array(
			'autofactura.php' => array('../ccgen/autofactura.php', 'Inicio'),
			'vta_clientes_facturas_search.php' => array('../ccgen/vta_clientes_facturas_search.php', 'Busqueda de Facturas'),
			'vta_clientes_facturas_form_cancel.php' => array('../ccgen/vta_clientes_facturas_form_cancel.php', 'Solicitud de Cancelación')
			//,
			//'vta_clientes_facturas_search_prev.php' => array('../prev/vta_clientes_facturas_search_prev.php', 'Busqueda de Facturas Anteriores')
		);
	}
	$text = "<!-- Fixed navbar -->
		  <div class=\"navbar navbar-default\" role=\"navigation\">
			<div class=\"container-fluid\">
			  <div class=\"navbar-header\">
				<button class=\"navbar-toggle\" data-target=\".navbar-collapse\" data-toggle=\"collapse\">
				<span class=\"sr-only\">Toggle navigation</span>
				<span class=\"icon-bar\">&nbsp;</span>
				<span class=\"icon-bar\">&nbsp;</span>
				<span class=\"icon-bar\">&nbsp;</span>
				</button> <a href=\"#\" class=\"navbar-brand\"><img src=\"../images/logos/logo_home.png?v=1\" width=\"96\" height=\"45\"></a> 
			  </div>
			  <div class=\"navbar-collapse collapse\">
			  <ul class=\"nav navbar-nav\">";
	foreach($a_opciones as $indice => $a_opt) {
		$link = $a_opt[0];
		$desc_menu = $a_opt[1];
		$class_active = (strlen($filename) and $filename == $indice) ? "class=\"active\"" : "";
		// $link_show = (strlen($filename) and $filename == $indice) ? "#" : $link;
		$link_show = (!strlen($filename)) ? "#" : $link;
		$text .= "<li $class_active><a href=\"$link_show\">$desc_menu</a></li>";
	}
    $text .= "</ul>
			  </div>
			  <!--/.nav-collapse -->
			</div>
		  </div>";
    return $text;
}

$es_mostrar_sin_acceso = 0;
function itemsBT($id_perfil, $es_perfil_admin, $id_menu, &$text, $is_deep = 0) {
	global $flags;
	global $es_mostrar_sin_acceso;
    $cont_mostr = 0;
    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, FileName, IdMenu AS Children, COALESCE(t3.IdApp, -1) AS IdApp, t1.EsAdmin
            FROM seg_menu t1
            LEFT JOIN seg_aplicaciones t2 ON
                t1.IdApp = t2.IdApp
            LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                t1.IdApp = t3.IdApp
            WHERE IdMenuUp = $id_menu AND EsActivo = 1 -- AND t3.IdApp IS NOT NULL
            ORDER BY Orden";
    $a_items = DbQryToArray($qry, true, true);
    // if ($is_deep == 0) {
        $text .= "<ul class=\"dropdown-menu multi-level\">" . PHP_EOL;
    // } else {
        // $text .= "<ul class=\"dropdown-menu\">" . PHP_EOL;
        // // $text .= "<ul>" . PHP_EOL;
    // }
    foreach ($a_items as $cd_mnu_i) {
        $cdi = $cd_mnu_i['IdMenu'];
		if ($es_mostrar_sin_acceso == 1) {
            $qry = "SELECT COUNT(*) FROM seg_menu WHERE IdMenuUp = $cdi AND EsActivo = 1";
        } else {
            $qry = "SELECT COUNT(*)
                    FROM seg_menu t1
                    LEFT JOIN seg_aplicaciones t2 ON
                        t1.IdApp = t2.IdApp
                    LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                        t1.IdApp = t3.IdApp
                    WHERE IdMenuUp = $cdi AND EsActivo = 1 AND COALESCE(t3.IdApp, -1) > 0";
        }
        $count = DbGetFirstFieldValue($qry);
		$desc_menu = htmlentities($cd_mnu_i['NombreMenu'], $flags, 'ISO8859-1');
		// if ($id_menu == 36) {
			// echo "<pre>" . $qry . "</pre>";
			// echo "IdMenu = $id_menu, count: $count" . PHP_EOL; exit();
		// }
        if ($cd_mnu_i['EsAdmin'] != 1 or $es_perfil_admin) {
            if ($count > 0) {
                $text .= "<li class=\"dropdown-submenu\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$desc_menu</a>" . PHP_EOL;
                $cont_mostr = $cont_mostr + itemsBT($id_perfil, $es_perfil_admin, $cd_mnu_i['Children'], $text, 1);
                $text .= "</li>" . PHP_EOL;
            } else {
				$desc_opt = $cd_mnu_i['NombreMenu'];
                $desc_opt = htmlentities($desc_opt, $flags, 'ISO8859-1');
                $es_acceso = $cd_mnu_i['IdApp'] > 0;
                if ($es_acceso) {
                    $link = $cd_mnu_i['FileName'];
					// onclick=\"document.getElementById('id_menu_selected').value = $cdi\"
					if (substr($link, 0, 12) == '../AlvicFac/') {
						$text .= "<li><a href=\"$link\" target=\"_top\">$desc_opt</a></li>" . PHP_EOL;
					} else {
						$text .= "<li><a href=\"$link\" target=\"mainFrame\">$desc_opt</a></li>" . PHP_EOL;
					}
					$cont_mostr++;
                } else {
					if ($es_mostrar_sin_acceso == 1) {
						$link = "#";
						$text .= "<li DISABLED><a href=\"$link\">$desc_opt</a></li>" . PHP_EOL;
						$cont_mostr++;
					}
                }
            }
        }
    }
    $text .= "</ul>" . PHP_EOL;
	return $cont_mostr;
}


function itemsAllBT($filename = "") {
	global $flags;
    $id_user = SessGetUserID();
    $qry = "SELECT t1.UsuarioPerfilId, t2.EsAdmin, t1.UserName
            FROM seg_usuarios t1
            LEFT JOIN seg_usuarioperfil t2 ON
                t1.UsuarioPerfilId = t2.UsuarioPerfilId
            WHERE IdUsuario = $id_user";
    $a_user = DbQryToRow($qry);

    $id_perfil = $a_user['UsuarioPerfilId'];
    $es_perfil_admin = ($a_user['EsAdmin'] == 1);
	$user_name = $a_user['UserName'];
	$login_app = "login.php";
	
	// // si tiene un paciente en la session, trata de leer su nombre_menu
	// if (isset($_SESSION['IdPaciente'])) {
		// $id_paciente = $_SESSION['IdPaciente'];
		// if (strlen($id_paciente)) {
			// $qry = "SELECT Nombre, ApellidoPaterno, ApellidoMaterno FROM pacientes WHERE IdPaciente = $id_paciente";
			// $a_paciente = DbQryToRow($qry);
			// $paciente = $a_paciente['Nombre'] . " " . $a_paciente['ApellidoPaterno'] . " " . $a_paciente['ApellidoMaterno'];
			// if (strlen(trim($paciente))) {
				// $user_name = $paciente;
				// $login_app = "login_pacientes.php";
			// }
		// }
	// }

	// $id_menu_bt = 124; // ojo fijo
    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, FileName, IdMenu AS Children, CASE WHEN t3.IdApp IS NULL THEN -1 ELSE t3.IdApp END AS IdApp, t1.EsAdmin
            FROM seg_menu t1
            LEFT JOIN seg_aplicaciones t2 ON
                t1.IdApp = t2.IdApp
            LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                t1.IdApp = t3.IdApp
            WHERE IdMenuUp IS NULL AND EsActivo = 1 -- AND t3.IdApp IS NOT NULL
            ORDER BY Orden";
    $a_main = DbQryToArray($qry, true, true);
	$text = "<!-- Fixed navbar -->
			<div class=\"navbar navbar-default navbar-fixed-top bg-primary navbar-dark\" role=\"navigation\">
				<div class=\"container\">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class=\"navbar-header\">
						<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
							<span class=\"sr-only\">Toggle navigation</span>
							  <span class=\"icon-bar\"></span>
							  <span class=\"icon-bar\"></span>
							  <span class=\"icon-bar\"></span>
						</button>
					  <a class=\"navbar-brand\" href=\"#\"><i class=\"icon-home icon-white\"> </i><img src=\"../images/logos/logo_home.png\" width=\"96\" height=\"45\"></a>
					</div>
					<div class=\"collapse navbar-collapse navbar-ex1-collapse\">
						<ul class=\"nav navbar-nav\">";
    foreach ($a_main AS $mnu) {
        if ($mnu['EsAdmin'] != 1 or $es_perfil_admin) {
			$desc_menu = $mnu['NombreMenu'];
            $desc_menu = htmlentities($desc_menu, $flags, 'ISO8859-1');
			$link = (strlen($mnu['FileName'])) ? $mnu['FileName'] : "#";
			$class_active = (strlen($filename) and $filename == $link) ? "active" : "";
			$classes = "class=\"menu-item dropdown $class_active\"";
			$link_show = (strlen($filename) and $filename == $link) ? "#" : $link;
            // $text .= "<li $classes><a href=\"$link_show\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$desc_menu<b class=\"caret\"></b></a>" . PHP_EOL;
            $text_b = "<li><a href=\"$link_show\" class=\"dropdown-toggle dropdown-head\" data-toggle=\"dropdown\">$desc_menu<b class=\"caret\"></b></a>" . PHP_EOL;
			$cont_mostr = itemsBT($id_perfil, $es_perfil_admin, $mnu['Children'], $text_b);
			$text_b .= "</li>";
			if ($cont_mostr == 0) {
				$text_b = "";
			}
			$text .= $text_b;
        }
    }
	
	$cd_param = "menu_mostrar_boton_cambia_cia";
	$qry = "SELECT value FROM knl_params WHERE cd_param = '$cd_param'";
	$mostrar_boton_cambia_cia = DbGetFirstFieldValue($qry);
		
	if ($mostrar_boton_cambia_cia == 1) {
		$text .= "<li><a href=\"../../\" class=\"dropdown-head\"><span>Cambiar de Compañia</span></a><li>";
	}
	
	// lee parametro de logout
	$qry = "SELECT value FROM knl_params WHERE cd_param = 'sys_logout_return'";
	$sys_logout_return_b = $login_app . "?Logout=true";
	$sys_logout_return = DbGetFirstFieldValue($qry);
	if ($sys_logout_return == '') {
		$sys_logout_return = $sys_logout_return_b;
	} else {
		$sys_logout_return = $sys_logout_return . $sys_logout_return_b;
	}

	$salir_label = "Salir";
	$text .= "<li><a href=\"$sys_logout_return\" class=\"dropdown-head\">$salir_label</a></li>";
    $text .= "</ul>
			  <ul class=\"nav navbar-nav navbar-right\">
				<li><a href=\"#\" class=\"dropdown-head\">$user_name</a></li>
			  </ul>
			  </div>
			  <!--/.nav-collapse -->
			</div>
		  </div><br><br><br>";
	// echo $text; exit();
    return $text;
}


function getMenuParent($id_menu, $time = 0) {
	$time++;
	$ruta_menu = "";
	$qry = "SELECT NombreMenu, IdMenuUp FROM seg_menu WHERE IdMenu = $id_menu";
	$a_row = DbQryToRow($qry);
	$nombre_menu = $a_row['NombreMenu'];
	$id_menu_parent = $a_row['IdMenuUp'];
	if (strlen($id_menu_parent)) {
		$ruta_menu = getMenuParent($id_menu_parent, $time);
	}
	if ($time != 1) {
		$ruta_menu .= $nombre_menu . " > ";
	}
	return $ruta_menu;
}

function getAppsActivas($id_user) {
    $qry = "SELECT t1.UsuarioPerfilId, t2.EsAdmin
            FROM seg_usuarios t1
            LEFT JOIN seg_usuarioperfil t2 ON
                t1.UsuarioPerfilId = t2.UsuarioPerfilId
            WHERE IdUsuario = $id_user";
    $a_user = DbQryToRow($qry);
    $id_perfil = $a_user['UsuarioPerfilId'];
    $es_perfil_admin = ($a_user['EsAdmin'] == 1);

    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, IdMenu AS Children, t1.EsAdmin
            FROM seg_menu t1
            WHERE IdMenuUp IS NULL AND EsActivo = 1
            ORDER BY Orden";
    $a_main = DbQryToArray($qry, true, true);
    $a_opcs = array();
    foreach ($a_main AS $mnu) {
        //if ($mnu['EsAdmin'] != 1 or $es_perfil_admin) {
        if ($mnu['EsAdmin'] != 1) {
            $desc_menu = htmlentities($mnu['NombreMenu'], ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
            getAppsActivasB($id_perfil, $es_perfil_admin, $mnu['Children'], $a_opcs);
        }
    }
    return $a_opcs;
}

function getAppsActivasB($id_perfil, $es_perfil_admin, $id_menu, &$a_opcs, $is_deep = 0) {
    $qry = "SELECT IdMenu, NombreMenu, IdMenuUp, FileName, IdMenu AS Children, CASE WHEN t3.IdApp IS NULL THEN -1 ELSE t3.IdApp END AS IdApp, t1.EsAdmin
            FROM seg_menu t1
            LEFT JOIN seg_aplicaciones t2 ON
                t1.IdApp = t2.IdApp
            LEFT JOIN (SELECT IdApp FROM seg_perfilesaplicaciones WHERE IdUsuarioPerfil = $id_perfil) t3 ON
                t1.IdApp = t3.IdApp
            WHERE IdMenuUp = $id_menu AND EsActivo = 1
            ORDER BY Orden";
    $a_items = DbQryToArray($qry, true, true);
    foreach ($a_items AS $cd_mnu_i) {
        $cdi = $cd_mnu_i['IdMenu'];
        $qry = "SELECT COUNT(*) FROM seg_menu WHERE IdMenuUp = $cdi AND EsActivo = 1";
        $count = DbGetFirstFieldValue($qry);
        $desc_menu = htmlentities($cd_mnu_i['NombreMenu'], ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
        //if ($cd_mnu_i['EsAdmin'] != 1 or $es_perfil_admin) {
        if ($cd_mnu_i['EsAdmin'] != 1) {
            if ($count > 0) {
                getAppsActivasB($id_perfil, $es_perfil_admin, $cd_mnu_i['Children'], $a_opcs, 1);
            } else {
                $desc_opt = htmlentities($cd_mnu_i['NombreMenu'], ENT_COMPAT | ENT_HTML401, 'ISO8859-1');
                $es_acceso = $cd_mnu_i['IdApp'] > 0;
                if ($es_acceso) {
					$a_opcs[] = $cd_mnu_i['IdApp'];
                }
            }
        }
    }
}

function encodePassword($str) {
	$ret = $str;
	if (strlen($str)) {
		$key1 = "dvdrw";
		$key2 = "cofee";
		$base = $key1 . $str . $key2;
		$ret = base64_encode(strrev($base));
	}
	return $ret;
}

function decodePassword($str) {
	$ret = $str;
	if (strlen($str)) {
		$key1 = "dvdrw";
		$key2 = "cofee";
		$ret = strrev(base64_decode($str));
		$a_base1 = explode($key1, $ret);
		echo "<pre>" . print_r($a_base1, true) . "</pre>";
		$a_base2 = explode($key2, $a_base1[1]);
		echo "<pre>" . print_r($a_base2, true) . "</pre>";
		$ret = $a_base2[0];
	}
	return $ret;
}

?>