<?php 
    
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");

session_start();

$custom_alert = "";
$error_alert = "";
if (isset($_POST['login'])) {
	$usuario = (isset($_POST['UserName'])) ? $_POST['UserName'] : '';
	$passwd = (isset($_POST['passwd'])) ? $_POST['passwd'] : '';
	if (strlen($usuario) and strlen($passwd)) {
		$qry = "SELECT IdUsuario, UserName, EsActivo, passwd FROM seg_usuarios WHERE UserName = '$usuario'";
		// echo $qry;
		$a_user = DbQryToRow($qry);
		if (count($a_user) > 0) {		
			$es_activo = $a_user['EsActivo'];
			if ($es_activo == 1) {
				$hashed_password = $a_user['passwd'];
				// if ($passwd_db == sha1($passwd)) {
				if (hash_equals($hashed_password, crypt($passwd, $hashed_password))) {
					$id_user = $a_user['IdUsuario'];
					$ret = setLogin($id_user, DtDbToday(), TmDbStamp());
					// ok
					if ($ret == 1) {
						redirect('nueva_solicitud.php');
					} else {
						$error_alert = 'Datos de acceso incorrectos'; // no hay acceso al nombre de session???
					}
				} else {
					$error_alert = 'Datos de acceso incorrectos';
				}
			} else {
				$error_alert = 'Usuario inactivo';
			}
		} else {
			$error_alert = 'Usuario No existe';
		}
	} else {
		if (!strlen($usuario)) {
			$error_alert = 'Debe completar el campo usuario';
		}
		if (!strlen($passwd)) {
			$error_alert .= (strlen($error_alert)) ? '<br>' : '';
			$error_alert .= 'Debe completar el campo Password';
		}
	}
} elseif (isset($_REQUEST['Logout'])) {
	global $session_name;
	$_SESSION[$session_name] = $id_user;
	session_destroy();
	session_unset();
	$custom_alert = 'Se ha cerrado la sesión';
}

if (strlen($custom_alert)) {
	$custom_alert = "<div class=\"alert alert-info\" role=\"alert\">$custom_alert</div>";
}
if (strlen($error_alert)) {
	$error_alert = "<div class=\"alert alert-warning\" role=\"alert\">$error_alert</div>";
}

?>
<?php include('../layouts/main.php'); ?>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-auto col-lg-3">
            <h2 class="text-primary text-center mb-4">PROGAS</h2>
				<?php
						if (strlen($error_alert)) { 
							echo $error_alert;
						}
						if (strlen($custom_alert)) { 
							echo $custom_alert;
						}
					?>
                <form method="post" class="shadow  py-4 px-3 mb-5 bg-body rounded">
                    <h4 class="text-center mb-3">LOGIN</h4>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="UserName" name="UserName">
                        <span class="input-group-text fas fa-user"></span>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Contraseña" name="passwd">
                        <span class="input-group-text fas fa-lock"></span>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="login" class="btn btn-sm w-50 btn-primary">Login</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

<?php include('../layouts/main_end.php'); ?>