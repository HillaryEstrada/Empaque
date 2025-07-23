<?php
    // Verifica si la solicitud es de tipo POST y si se han recibido los parámetros necesarios
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pk']) && isset($_POST['tabla']) && isset($_POST['pkname'])) {
        $pk = $_POST['pk']; // Identificador del elemento a desactivar
        $tabla = $_POST['tabla']; // Nombre de la tabla en la base de datos
        $pkname = $_POST['pkname']; // Nombre de la clave primaria en la tabla

        // Llamamos a la función genérica del modelo para desactivar el elemento
        $respuesta = Modelo::desactivarElementoModelo($pk, $tabla, $pkname);

        // Si la desactivación fue exitosa, muestra una notificación de éxito
        if ($respuesta === 'ok') {
            ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Se desactivó correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Redirige a la página correspondiente con la opción de mostrar la tabla actualizada
                    postToExternalSite('index.php', {opcion: 'mostrar_<?php echo htmlspecialchars($tabla); ?>'});
                });
            </script>
            <?php
        } else { 
            // Si hubo un error en la desactivación, muestra una alerta de error
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocurrió un error al desactivar.'
                });
            </script>
            <?php
        }
    } else { 
        // Si no se proporcionaron los datos requeridos, muestra un mensaje de acceso no permitido
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso no permitido',
                text: 'No se ha proporcionado la información necesaria.'
            }).then(() => {
                /* No redirige correctamente porque la opción está vacía, 
                ya que este es un script genérico */
                postToExternalSite('index.php', {opcion: ''});
            });
        </script>
        <?php
    }
?>
