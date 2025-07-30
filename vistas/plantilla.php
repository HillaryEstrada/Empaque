<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empaque de Mango</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/ae1b5f3a79.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vistas\css\plantilla.css">
    <link rel="stylesheet" href="vistas/css/mostrar-mango.css">
    <link rel="stylesheet" href="vistas/css/sweetalert-custom.css">
    <link rel="stylesheet" href="vistas/css/pagina-principal.css">
    <link rel="stylesheet" href="vistas/css/footer.css">
    <link rel="stylesheet" href="vistas\css\menu-mango.css">

    <!-- Font Awesome para iconos login actualizado-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <!-- Aquí va el menú -->
    <div>
        <?php require("modulos/menu.php"); ?>
    </div>

    <!-- Contenedor principal responsivo -->
    <div class="w-100 content mt-2">

        <?php
            // Conexión a base de datos
            $x = new Controlador();
            $x->enlacesPaginasControlador(); // x llama a todos los métodos, -> especifica uno solo
        ?>
    </div>

    <!-- Aquí va el pie de página web estático -->
    

    <!--Llamar los archivos script-->
    <script src="vistas\js\menu.js"></script>
    <script src="vistas\js\proceso_tabla.js"></script>
    
</body>
</html>
