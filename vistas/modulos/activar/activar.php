<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pk']) && isset($_POST['tabla']) && isset($_POST['pkname']) && isset($_POST['estado'])) {
    $pk = $_POST['pk'];
    $tabla = $_POST['tabla'];
    $pkname = $_POST['pkname'];
    $estado = $_POST['estado']; // Se recibe el estado actual para redirigir correctamente

    // Llamamos a la función genérica del modelo
    $respuesta = Modelo::activarElementoModelo($pk, $tabla, $pkname);

    // Respuesta según el resultado del modelo
    if ($respuesta === 'ok') {
        ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Se activó correctamente',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                postToExternalSite('index.php', { 
                    opcion: 'mostrar_<?php echo htmlspecialchars($tabla); ?>', 
                    estado: '<?php echo $estado; ?>' // Mantiene la vista en inactivos si se activó desde ahí
                });
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ocurrió un error al activar.'
            });
        </script>
        <?php
    }
} else {
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acceso no permitido',
            text: 'No se ha proporcionado la información necesaria.'
        }).then(() => {
            postToExternalSite('index.php', {opcion: ''});
        });
    </script>
    <?php
}
?>
