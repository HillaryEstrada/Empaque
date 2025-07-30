<?php
class controladorAcceso{
    
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = trim($_POST["usuario"] ?? '');
            $contrasena = $_POST["contrasena"] ?? '';

            // Validar campos vacíos
            if (empty($usuario) || empty($contrasena)) {
                $_SESSION["error_login"] = "Por favor, completa todos los campos.";
                header("Location: vistas/modulos/login.php");
                exit();
            }

            try {
                $db = Conexion::conectar();
                $stmt = $db->prepare("
                    SELECT u.pk_usuario, u.usuario, u.contrasena, u.fk_rol, r.nombre AS rol, d.nombre AS nombre_usuario
                    FROM usuario u
                    JOIN rol r ON u.fk_rol = r.pk_rol
                    JOIN dato_usuario d ON u.fk_dato_usuario = d.pk_dato_usuario
                    WHERE u.usuario = :usuario AND u.estado = 1
                ");
                $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
                $stmt->execute();
                $datos = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($datos && password_verify($contrasena, $datos["contrasena"])) {
                    // Crear sesión
                    $_SESSION["id_usuario"] = $datos["pk_usuario"];
                    $_SESSION["usuario"] = $datos["usuario"];
                    $_SESSION["nombre"] = $datos["nombre_usuario"];
                    $_SESSION["rol"] = $datos["rol"];

                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION["error_login"] = "Usuario o contraseña incorrectos.";
                    $_SESSION["info_credenciales"] = "Si olvidaste tus credenciales, por favor comunícate con el administrador o soporte para la recuperación de credenciales.";
                    header("Location: vistas/modulos/login.php");
                    exit();
                }

            } catch (PDOException $e) {
                error_log("Error en login: " . $e->getMessage());
                $_SESSION["error_login"] = "Ocurrió un error interno. Intenta más tarde.";
                header("Location: vistas/modulos/login.php");
                exit();
            }
        } else {
            header("Location: vistas/modulos/login.php");
            exit();
        }
    }
}
