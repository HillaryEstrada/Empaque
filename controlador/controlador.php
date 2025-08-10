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
        $respuesta = Modelo::obtenerRutaPorNombre($enlace);
        if ($respuesta && file_exists($respuesta['ruta'])) {
            include $respuesta['ruta'];
        } else {
            echo "<h2>Error: No se encontró el archivo especificado.</h2>";
        }
    }
    }
?>