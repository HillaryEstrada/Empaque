<?php

session_start();

require_once("modelo/enlaces.php");
require_once("modelo/modelo.php");
require_once("controlador/controlador.php");
require_once("controlador/controladorAcceso.php");


require_once("controlador/controladorUsuario.php");
require_once("modelo/modeloUsuario.php");



require_once("controlador/controladorLlegada.php");
require_once("modelo/modeloLlegada.php");

require_once("controlador/controladorRancho.php");
require_once("modelo/modeloRancho.php");

require_once("controlador/controladorCalidad.php");
require_once("modelo/modeloCalidad.php");

require_once("controlador/controladorGasto.php");
require_once("modelo/modeloGasto.php");



// Login antes de cargar la vista principal
if (($_POST["opcion"] ?? $_GET["opcion"] ?? '') === "login") {
    $mvc = new controladorAcceso();
    $mvc->login();
    exit();
}

// Verificar sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: vistas/modulos/login.php");
    exit();
}

//instanciar la clase controlador en un objeto
$mvc = new Controlador();

//llamamos al metodo pagina de la clase controlador
$mvc -> pagina();