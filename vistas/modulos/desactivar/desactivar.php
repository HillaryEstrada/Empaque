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
                    icon: "success",
                    title: "¡Desactivado!",
                    text: "El elemento se desactivó correctamente",
                    confirmButtonText: "Continuar",
                    confirmButtonColor: "#28a745",
                    background: "#ffffff",
                    color: "#333333",
                    iconColor: "#28a745",
                    width: "500px",
                    padding: "2rem",
                    backdrop: "rgba(0,0,0,0.4)",
                    allowOutsideClick: false,
                    customClass: {
                        popup: "colored-toast"
                    }
                }).then(() => {
                    postToExternalSite('index.php', {opcion: 'mostrar_<?php echo htmlspecialchars($tabla); ?>'});
                });
            </script>
            <?php
        } else { 
            ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "Ocurrió un error al desactivar el elemento",
                    confirmButtonText: "Entendido",
                    confirmButtonColor: "#dc3545",
                    background: "#ffffff",
                    color: "#333333",
                    iconColor: "#dc3545",
                    width: "500px",
                    padding: "2rem",
                    backdrop: "rgba(0,0,0,0.4)",
                    allowOutsideClick: false,
                    customClass: {
                        popup: "colored-toast"
                    }
                });
            </script>
            <?php
        }
    } else { 
        ?>
        <script>
            Swal.fire({
                icon: "warning",
                title: "¡Acceso Denegado!",
                text: "No se ha proporcionado la información necesaria",
                confirmButtonText: "Entendido",
                confirmButtonColor: "#ffc107",
                background: "#ffffff",
                color: "#333333",
                iconColor: "#ffc107",
                width: "500px",
                padding: "2rem",
                backdrop: "rgba(0,0,0,0.4)",
                allowOutsideClick: false,
                customClass: {
                    popup: "colored-toast"
                }
            }).then(() => {
                postToExternalSite('index.php', {opcion: ''});
            });
        </script>
        <?php
    }
?>
