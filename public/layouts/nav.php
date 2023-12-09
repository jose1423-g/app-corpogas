<?php
    $id_user = SessGetUserId();

    $qry = "SELECT UserName FROM seg_usuarios WHERE IdUsuario = $id_user";
    $user_name  =  DbGetFirstFieldValue($qry);

?>
<nav class="navbar bg-white shadow-sm position-sticky top-0 z-3" style="height: 3rem;">
    <div class="container-fluid">
        <button type="button" class="btn border-0"  id="btn-menu"><i class="fs-6 fas fa-bars"></i></button>
    
        <div class="btn-group dropstart">
            <button type="button" class="border-0 bg-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="far fa-user-circle fs-5"></i>
            </button>
            <ul class="dropdown-menu">
                <span class="dropdown-item dropdown-header"><?php echo (isset($g_nombre_usuario)) ? utf8_encode($g_nombre_usuario) : 'Menu usuario'; ?></span>
                <div class="dropdown-divider"></div>
                <li><a href="#" class="dropdown-item"><i class="fas fa-user"></i><?php echo $user_name; ?></a></li>
                <li><a href="usuario_perfil.php" class="dropdown-item"><i class="fas fa-user-secret"></i>Cambia Password</a></li>
                <li><a class="dropdown-item" href="login.php?Logout=true"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
            </ul>
        </div>
    </div>
</nav>