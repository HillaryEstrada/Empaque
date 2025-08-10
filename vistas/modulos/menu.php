<?php 
// views/breadcrumbs.php
require_once 'controlador/controlador.php';

// Obtener la opción desde POST o un valor por defecto
$opcion = $_POST['opcion'] ?? 'principal';
?>

<!-- Barra de navegación fija en la parte superior de la página -->
<nav class="navbar navbar-expand-lg fixed-top w-100">
    <div class="w-100 p-0 m-0" style="max-width:100vw;">
        <!-- Botón para mostrar/ocultar el menú en pantallas pequeñas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenedor del menú desplegable -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Lista de elementos del menú -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Opción de menú principal -->
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="postToExternalSite('#', { opcion: 'principal' });">
                        <i class="fas fa-home"></i>
                    </a>
                </li>

                <!-- Opción de enlace -->
                <?php if (puedeVer('administrador')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-link"></i> Link
                    </a>
                </li>
                <?php endif; ?>


                <!-- Menú desplegable para 'Mostrar' -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMostrar" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-eye"></i> Mostrar
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMostrar">
                        
                        <?php if (puedeVer('administrador')): ?>
                        <li>
                            <a class="dropdown-item" href="#" onclick="postToExternalSite('index.php', { opcion: 'mostrar_dato_usuario' });">
                                <i class="fas fa-list"></i> Dato de Usuario
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li>
                            <a class="dropdown-item" href="#" onclick="postToExternalSite('index.php', { opcion: 'mostrar_productor' });">
                                <i class="fas fa-list"></i> Productor
                            </a>
                        </li>


                        
                    </ul>

                </li>

             

                     <!-- Menú desplegable para 'ROL' -->
                <?php if (puedeVer('administrador')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMostrar" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-eye"></i> Analisis PDF
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMostrar">
                        <li>
                            <a class="dropdown-item" href="#" onclick="postToExternalSite('index.php', { opcion: 'pdf' });">
                                <i class="fas fa-list"></i> ROL
                            </a>
                        </li>
                      
                    </ul>
                </li>
                <?php endif; ?>


            </ul>

            <!-- Menú de usuario -->
            <?php if (!isset($_SESSION['rol'])): ?>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-success" id="loginBtn" type="button">
                        <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                    </button>
                </div>
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    var loginBtn = document.getElementById('loginBtn');
                    var loginModal = document.getElementById('loginModal');
                    if (loginBtn && loginModal) {
                      loginBtn.onclick = function() {
                        loginModal.style.display = 'flex';
                      }
                    }
                  });
                </script>
            <?php else: ?>
                <div class="d-flex align-items-center">
                    <span class="text-light me-3">
                        <i class="fas fa-user"></i>
                        Bienvenido, <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : $_SESSION['usuario']; ?>
                        <small>(<?php echo $_SESSION['rol']; ?>)</small>
                    </span>
                    <button class="btn btn-outline-light" onclick="confirmarCerrarSesion()">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
// FUNCIÓN PARA CONTROLAR VISIBILIDAD SEGÚN ROL
function puedeVer($rolPermitido) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rolPermitido;
}
?>
