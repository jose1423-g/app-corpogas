  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo (isset($g_nombre_usuario)) ? utf8_encode($g_nombre_usuario) : 'Menu usuario'; ?></span>
          <div class="dropdown-divider"></div>
          <a href="usuario_perfil.php" class="dropdown-item">
            <i class="fas fa-user-secret"></i> Cambia Password
          </a>
          <a href="login.php?Logout=true" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> Salir
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../images/logos/logo.png?v=2"
           alt="Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">E Stats</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
		  </li>
		  <li class="nav-item">
			<a href="archivos-lista.php" class="nav-link">
			  <i class="nav-icon fas fa-file-alt"></i>
			  <p>Lista de archivos</p>
			</a>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Config
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="ftp-config.php" class="nav-link">
				  <i class="nav-icon fas fa-network-wired"></i>
				  <p>
					Datos FTP
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="usuarios.php" class="nav-link">
				  <i class="nav-icon fas fa-users"></i>
				  <p>
					Usuarios
				  </p>
				</a>
			  </li>
			</ul>
		  </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>