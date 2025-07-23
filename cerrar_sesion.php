<?php
session_start();

// Establecer mensaje de logout antes de destruir la sesión
$_SESSION['mensaje_logout'] = 'Sesión cerrada correctamente. ¡Hasta pronto!';

// Guardar el mensaje temporalmente
$mensaje = $_SESSION['mensaje_logout'];

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, también se debe borrar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Iniciar una nueva sesión solo para el mensaje
session_start();
$_SESSION['mensaje_logout'] = $mensaje;

// Redirigir al login
header("Location: vistas/modulos/login.php");
exit();
?>