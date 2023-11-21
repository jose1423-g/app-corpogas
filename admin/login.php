<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/seg_sys.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");

session_start();

// cia (page title)
// $qry = "SELECT PagesTitle FROM empresa WHERE EmpresaID = 1";
// $title = DbGetFirstFieldValue($qry);
// $title = (strlen($title)) ? $title : "Doxa";

$custom_alert = "";
$error_alert = "";
if (isset($_POST['login'])) {
	$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
	$passwd = (isset($_POST['passwd'])) ? $_POST['passwd'] : '';
	if (strlen($usuario) and strlen($passwd)) {
		$qry = "SELECT IdUsuario, passwd, EsActivo FROM usuarios WHERE UserName = '$usuario'";
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
						redirect('index.php');
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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->


  <!-- Theme style -->
  <link rel="stylesheet" href="../vendor/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
</head>
<body class="hold-transition login-page">
<?php
	if (strlen($error_alert)) { 
		echo $error_alert;
	}
	if (strlen($custom_alert)) { 
		echo $custom_alert;
	}
?>
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>E</b> Stats</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Login</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuario" name="usuario">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="passwd">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <!-- <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Recordarme
              </label>
            </div> -->
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
  <div class="login-logo">
    <a href="#"><img src="../images/logos/logo_home.png" width="100" alt="Logo"></a>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
</body>
</html>
