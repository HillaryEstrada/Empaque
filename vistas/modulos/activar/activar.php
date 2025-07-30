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
                icon: "success",
                title: "¡Activado!",
                text: "El elemento se activó correctamente",
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
                postToExternalSite('index.php', { 
                    opcion: 'mostrar_<?php echo htmlspecialchars($tabla); ?>', 
                    estado: '<?php echo $estado; ?>'
                });
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "Ocurrió un error al activar el elemento",
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
