<?php

    class Controlador
    {
        # llamada a la plantilla
    static public function pagina()
    {
        include "vistas/plantilla.php";
    }

    # Llamada a los diversos módulos
    public static function enlacesPaginasControlador()
    {

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: vistas/modulos/login.php");
            exit();
        }

        // Buscar 'opcion' en $_POST primero, luego en $_GET, por defecto 'principal'
        $enlace = $_POST["opcion"] ?? $_GET["opcion"] ?? 'principal';

        // Si es la página principal, verificar el rol del usuario
        if ($enlace === 'principal') {
            $rol = $_SESSION['rol'] ?? '';
            
            if ($rol === 'empleado') {
                // Cargar dashboard específico para empleado
                include 'vistas/modulos/principal-empleado.php';
                return;
            }
            // Si es administrador o cualquier otro rol, continúa con el flujo normal
        }

        // Consultamos la base de datos para obtener la ruta correspondiente a la opción
        $respuesta = Modelo::obtenerRutaPorNombre($enlace);

        // Verificar que la respuesta tiene la ruta
        if ($respuesta && file_exists($respuesta['ruta'])) {
            include $respuesta['ruta'];
        } else {
            echo "<h2>Error: No se encontró el archivo especificado.</h2>";
        }
    }
    }
?>