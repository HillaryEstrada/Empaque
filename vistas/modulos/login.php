<?php
session_start();

// Verificar si ya hay una sesión activa
if (isset($_SESSION["id_usuario"])) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpaqueMango Pro - Iniciar Sesión</title>
    
    <!-- Estilos externos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="login-container">
        <div class="welcome-section">
            <div class="logo-container">
                <img src="../../vistas/img/haden.jpg" alt="Logo Mango Empacadora">
            </div>
            <h1>EmpaqueMango Pro</h1>
            <p>Sistema integral de gestión para empacadoras de mango</p>
            <ul class="features-list">
                <li><i class="fas fa-users"></i> Gestión de usuarios y roles</li>
                <li><i class="fas fa-user-tie"></i> Control de productores</li>
                <li><i class="fas fa-file-pdf"></i> Reportes automatizados</li>
                <li><i class="fas fa-shield-alt"></i> Seguridad avanzada</li>
                <li><i class="fas fa-chart-line"></i> Análisis de productividad</li>
            </ul>
            <?php if (isset($_SESSION["info_credenciales"])): ?>
                <div class="info-message">
                    <i class="fas fa-info-circle"></i> 
                    <?php echo $_SESSION["info_credenciales"]; ?>
                </div>
                <?php unset($_SESSION["info_credenciales"]); ?>
            <?php endif; ?>
        </div>
        
        <div class="login-section">
            <div class="login-header">
                <h2>Iniciar Sesión</h2>
                <p>Accede a tu cuenta para continuar</p>
            </div>
            
            <?php if (isset($_SESSION["error_login"])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $_SESSION["error_login"]; ?>
                </div>
                <?php unset($_SESSION["error_login"]); ?>
            <?php endif; ?>
            
            <form action="../../index.php" method="POST">
                <input type="hidden" name="opcion" value="login">
                
                <div class="form-group">
                    <label for="usuario" class="form-label">
                        <i class="fas fa-user"></i>Usuario
                    </label>
                    <input 
                        type="text" 
                        name="usuario" 
                        id="usuario" 
                        class="form-control" 
                        required 
                        placeholder="Ingresa tu nombre de usuario"
                        autocomplete="username"
                        autocapitalize="off"
                    >
                </div>
                
                <div class="form-group">
                    <label for="contrasena" class="form-label">
                        <i class="fas fa-lock"></i>Contraseña
                    </label>
                    <input 
                        type="password" 
                        name="contrasena" 
                        id="contrasena" 
                        class="form-control" 
                        required 
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                    >
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Ingresar al Sistema
                </button>
            </form>
        </div>
    </div>


    
    <!-- Script de validación del formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enfocar el campo de usuario al cargar la página
            document.getElementById('usuario').focus();
        });
        
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const contrasena = document.getElementById('contrasena').value;
            
            if (usuario === '' || contrasena === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos Requeridos',
                    text: 'Por favor completa todos los campos',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#FF6B35',
                    background: '#ffffff',
                    color: '#2c3e50',
                    iconColor: '#ffc107',
                    borderRadius: '12px',
                    customClass: {
                        popup: 'swal-custom'
                    }
                });
            }
        });
    </script>
</body>
</html>