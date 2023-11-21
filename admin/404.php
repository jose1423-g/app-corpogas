<?php 


$title = "Página no encontrada";
$title_app = "Página no encontrada";
$error_alert = "<strong>Página no encontrada</strong>La página solicitada no existe. <button onclick=\"location.href='/AlvicFac';\" class=\"btn btn-success\">Inicio</button>";
$error_alert = "<div class=\"alert alert-info\" role=\"alert\">$error_alert</div>";
$user_data = array();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.css">
  <link rel="stylesheet" href="bower_components/jquery-ui/themes/base/jquery-ui.css">
  
  <!-- DataTables CSS -->
  <link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
	#rfc-container {
		position:relative;
		top: 30px;
	} 
	.ui-autocomplete {
		position: absolute;
	}
	.btn-info.ine {
	  background-color: #E62C9F;
	  border-color: #d62994;
	}
	.btn-info.ine:hover{
			background-color: #C41B84;
			border-color: #BA147B;
	}
	
	/* When the body has the loading class, we turn
	   the scrollbar off with overflow:hidden */
	body.loading {
		overflow: hidden;   
	}

	/* Anytime the body has the loading class, our
	   modal element will be visible */
	body.loading .modal-loading {
		display: block;
			text-align: center;
	}
	.modal-loading {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        0;
		left:       0;
		height:     100%;
		width:      100%;
		background: rgba( 255, 255, 255, .87 ) 
					url('dist/img/wait31.gif')
					50% 50% 
					no-repeat;
			background-image: url('dist/img/wait31.gif'), url('dist/img/espere.gif');
			background-position: 50% 75%, 50% 85%;
	}
	.form1{
		box-sizing:border-box;
	}
	.form2{
        text-align: center; /* right */
        padding: 0px;
	}
	
	@media all and (max-width:480px) {
	   .btn-block-doxa { width: 100%; display:block; }
	   .btn-lg-doxa { padding: 10px 16px; font-size:18px; line-height: 1.3333333;}
	}  
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini"><!-- can be fixed class -->
<div class="wrapper">

  <!-- Header -->

  <!-- Left side column. contains the logo and sidebar -->

  <!-- Starts Main Page -->
  <!-- Content Wrapper. Contains page content -->
 <div class="container-fluid" style="min-height: 606px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title_app; ?>
        <!--<small>Preview</small> -->
      </h1>
	  <?php echo $error_alert; ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-9 form1">
          <!-- general form elements -->
          <div class="box box-primary">
              <div class="box-body">
			  </div>
			</div>
          <!-- /.box -->
        </div>
        <div class="col-md-3 form2" id="advert_div">
        </div>
      </div>
      <!-- /.row -->
    </section>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
	  <span class="text-muted">La utilizaci&oacute;n de este Sitio Web implica que usted ha le&iacute;do nuestro <a href="Privacidad.pdf" target="_blank">Aviso de Privacidad</a>.</span>
  </footer>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<div class="modal modal-loading">
</div>
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables JavaScript -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>