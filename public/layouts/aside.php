<aside class="vh-100 bg-dark text-white menu z-3" id="content-menu" style="width: 4.5rem;">
    <ul class="nav flex-column">
        <li class="nav-item mb-4 menu-item">
            <a class="border-bottom d-flex justify-content-center align-items-center text-white text-decoration-none px-3" style="height: 3rem;" href="nueva_solicitud.php">
                <i class="nav-icon fs-5 fas fa-home"></i>
                <p class="d-none ms-3 mb-0">PROGAS</p>
            </a>
        </li>        
        <li class="nav-item  menu-item" id="solicitudes">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="#">
                <i class="fas fa-chevron-circle-down"></i>
                <p class="d-none ms-3 mb-0">Solicitudes</p>
            </a>
        </li> 
        <div class="bg-secondary py-2" id="content-solicitudes" style="display: none;">
            <li class="nav-item mb-3 menu-item">
                <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="nueva_solicitud.php">
                    <i class="nav-icon fs-5 fas fa-file-alt"></i>
                    <p class="d-none ms-3 mb-0">Nueva Solicitud</p>
                </a>
            </li> 
            <li class="nav-item mb-3 menu-item">
                <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="solicitudes_pendientes.php">                    
                    <i class="nav-icon fs-5 fas fa-list-ul"></i>
                    <p class="d-none ms-3 mb-0">Estatus solicitud</p>
                </a>
            </li>
            <li class="nav-item menu-item">
                <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="reporte_solicitudes.php">
                    <i class="nav-icon fs-5 fas fa-file-alt"></i>
                    <p class="d-none ms-3 mb-0">Reporte de solicitudes</p>
                </a>
            </li>
        </div>
        <!-- nav hidden end -->
        <li class="nav-item mt-4 menu-item mb-4">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="seg_estaciones.php">
                <i class="nav-icon fs-5 fas fa-plus"></i>
                <p class="d-none ms-3 mb-0">Nueva estacion</p>
            </a>
        </li>
        <li class="nav-item menu-item mb-4">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="usuarios.php">
                <i class="nav-icon fs-5 fas fa-user"></i>
                <p class="d-none ms-3 mb-0">Usuarios</p>
            </a>
        </li>
        <!-- nav hidden -->
        <li class="nav-item  menu-item" id="perfiles">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="#">
                <i class="fas fa-chevron-circle-down"></i>
                <p class="d-none ms-3 mb-0">Perfiles</p>
            </a>
        </li> 
        <div class="bg-secondary py-2" id="content-perfiles" style="display: none;">
            <li class="nav-item menu-item mb-3">
                <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="perfiles.php">
                    <i class="nav-icon fs-5 fas fa-plus"></i>
                    <p class="d-none ms-3 mb-0">Nuevo perfil</p>
                </a>
            </li>
            <li class="nav-item menu-item">
                <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="seg_perfilesaplicaciones.php">
                    <i class="nav-icon fs-5 fas fa-users"></i>
                    <p class="d-none ms-3 mb-0">permisos a perfiles</p>
                </a>
            </li>
        </div>
        <!-- nav hidden end -->
        
        <li class="nav-item mt-4 menu-item">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="seg_categorias.php">
                <i class="nav-icon fs-5 fas fa-list-alt"></i>
                <p class="d-none ms-3 mb-0">Categorias</p>
            </a>
        </li> 
        <li class="nav-item mt-4 menu-item">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="seg_productos.php">
                <!-- <i class="nav-icon fs-5 fas fa-home"></i> -->
                <i class="nav-icon fs-5 fas fa-plus"></i>
                <p class="d-none ms-3 mb-0">Nueva Refaccion</p>
            </a>
        </li>
        <li class="nav-item mt-4 menu-item">
            <a class="d-flex justify-content-center align-items-center text-white text-decoration-none px-3" href="lista_productos.php">
                <!-- <i class="nav-icon fs-5 fas fa-home"></i> -->
                <i class="nav-icon fs-5 fas fa-list-ul"></i>
                <p class="d-none ms-3 mb-0">Productos</p>
            </a>
        </li>        
    </ul>
</aside>