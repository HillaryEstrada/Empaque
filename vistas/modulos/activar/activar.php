<?php
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['pk'], $_POST['tabla'], $_POST['pkname'], $_POST['estado'])
) {
    $pk = $_POST['pk'];
    $tabla = $_POST['tabla'];
    $pkname = $_POST['pkname'];
    $estado = $_POST['estado'];

    $respuesta = Modelo::activarElementoModelo($pk, $tabla, $pkname);

    if ($respuesta === 'ok') {
        ?>
        <form id="redirectForm" method="POST" action="index.php">
            <input type="hidden" name="opcion" value="mostrar_<?php echo htmlspecialchars($tabla); ?>">
            <input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado); ?>">
            <input type="hidden" name="alerta" value="activado">
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
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
        });
    </script>
    <?php
}
?>
