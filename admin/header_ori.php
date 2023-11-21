<?php
$is_show_notification = 0; // para mas adelante
?>
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
	<?php if ($is_show_notification == 1) { ?>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
	<?php } ?>
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

      <!-- DATA WIDGET ... <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/logo.png?v=2"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo $title; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo (isset($index_active)) ? $index_active : ''; ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
		  </li>
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo (isset($dashboard_active)) ? $dashboard_active : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
		  </li>
		  <li class="nav-item">
			<a href="productos.php" class="nav-link <?php echo (isset($productos_active)) ? $productos_active : ''; ?>">
			  <i class="nav-icon fas fa-cubes"></i>
			  <p>Productos</p>
			</a>
		  </li>
		  <li class="nav-item">
			<a href="productos-precios-clientes.php" class="nav-link <?php echo (isset($$productos_prec_cli_active)) ? $$productos_prec_cli_active : ''; ?>">
			  <i class="nav-icon fas fa-user-tag"></i>
			  <p>Precios por Cliente</p>
			</a>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-book"></i>
              <p>
                Contactos
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="clientes.php" class="nav-link <?php echo (isset($clientes_active)) ? $clientes_active : ''; ?>">
				  <i class="nav-icon fas fa-users"></i>
				  <p>
					Clientes
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="proveedores.php" class="nav-link <?php echo (isset($proveedores_active)) ? $proveedores_active : ''; ?>">
				  <i class="nav-icon fas fa-id-badge"></i>
				  <p>
					Proveedores
				  </p>
				</a>
			  </li>
			</ul>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Compras
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="compras.php" class="nav-link <?php echo (isset($compras_active)) ? $compras_active : ''; ?>">
				  <i class="nav-icon fas fa-truck"></i>
				  <p>
					Nueva Compra
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="compras-lista.php" class="nav-link <?php echo (isset($compras_lista_active)) ? $compras_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Lista de Compras
				  </p>
				</a>
			  </li>
			</ul>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Ventas
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="ventas.php" class="nav-link <?php echo (isset($ventas_active)) ? $ventas_active : ''; ?>">
				  <i class="nav-icon fas fa-shopping-cart"></i>
				  <p>
					Nueva Venta
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="ventas-lista.php" class="nav-link <?php echo (isset($ventas_lista_active)) ? $ventas_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Lista de Ventas
				  </p>
				</a>
			  </li>
			  
			  <li class="nav-item">
				<a href="cotizaciones.php" class="nav-link <?php echo (isset($cotizaciones_active)) ? $cotizaciones_active : ''; ?>">
				  <i class="nav-icon fas fa-file-invoice"></i>
				  <p>
					Nueva Cotizaci&oacute;n
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="cotizaciones-lista.php" class="nav-link <?php echo (isset($cotizaciones_lista_active)) ? $cotizaciones_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Lista de Cotizaciones
				  </p>
				</a>
			  </li>
			  
			</ul>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-dollar-sign"></i>
              <p>
                Crédito y Cobranza
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
			<ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="cxc-lista.php" class="nav-link <?php echo (isset($cxc_lista_active)) ? $cxc_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-users"></i>
				  <p>
					Cuentas por Cobrar
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="cxp-lista.php" class="nav-link <?php echo (isset($cxp_lista_active)) ? $cxp_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-id-badge"></i>
				  <p>
					Cuentas por Pagar
				  </p>
				</a>
			  </li>
			</ul>
		  </li>
		  
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Inventario
				<i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
			  <li class="nav-item">
				<a href="kardex.php" class="nav-link">
				  <i class="nav-icon fas fa-dolly"></i>
				  <p>
					Movimientos Almacen
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="inventario-fisico.php" class="nav-link <?php echo (isset($inv_fisico_active)) ? $inv_fisico_active : ''; ?>">
				  <i class="nav-icon fas fa-clipboard-check"></i>
				  <p>
					Inventario Fisico
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="inventario-fisico-lista.php" class="nav-link <?php echo (isset($inv_fisico_lista_active)) ? $inv_fisico_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Lista de Inv. Fisico
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="ajustes.php" class="nav-link <?php echo (isset($ajustes_active)) ? $ajustes_active : ''; ?>">
				  <i class="nav-icon fas fa-retweet"></i>
				  <p>
					Ajustes
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="ajustes-lista.php" class="nav-link <?php echo (isset($ajustes_lista_active)) ? $ajustes_lista_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Lista de Ajustes
				  </p>
				</a>
			  </li>
			</ul>
		  </li>
		  
          <li class="nav-item">
            <a href="gastos.php" class="nav-link">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Gastos
              </p>
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
				<a href="unidades-medida.php" class="nav-link <?php echo (isset($um_active)) ? $um_active : ''; ?>">
				  <i class="nav-icon fas fa-ruler"></i>
				  <p>
					Unidades Medida
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="tipos-ajustes.php" class="nav-link <?php echo (isset($tipos_ajustes_active)) ? $tipos_ajustes_active : ''; ?>">
				  <i class="nav-icon fas fa-list"></i>
				  <p>
					Tipos Ajuste
				  </p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="usuarios.php" class="nav-link <?php echo (isset($usuarios_active)) ? $usuarios_active : ''; ?>">
				  <i class="nav-icon fas fa-user-cog"></i>
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