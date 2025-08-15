<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que tenemos los parámetros básicos
    if (!isset($_POST['tablas']) || !isset($_POST['redirect_tabla']) || !isset($_POST['estado_redirect'])) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso no permitido',
                text: 'No se ha proporcionado la información necesaria.'
            });
        </script>
        <?php
        exit;
    }

    $tablas = $_POST['tablas']; // Formato: "tabla1:pk1:campo1,tabla2:pk2:campo2"
    $redirect_tabla = $_POST['redirect_tabla']; // Tabla para el redirect
    $estado_redirect = $_POST['estado_redirect']; // Estado para el redirect
    
    // Dividir las tablas
    $tablas_array = explode(',', $tablas);
    $resultados = array();
    $error = false;
    
    try {
        foreach ($tablas_array as $tabla_info) {
            $partes = explode(':', $tabla_info);
            if (count($partes) !== 3) {
                throw new Exception("Formato de tabla incorrecto: $tabla_info");
            }
            
            $tabla = trim($partes[0]);
            $pk_value = trim($partes[1]);
            $pk_name = trim($partes[2]);
            
            if (empty($tabla) || empty($pk_value) || empty($pk_name)) {
                throw new Exception("Parámetros vacíos en: $tabla_info");
            }
            
            // Usar la función genérica existente
            $resultado = Modelo::activarElementoModelo($pk_value, $tabla, $pk_name);
            $resultados[] = $resultado;
            
            if ($resultado !== 'ok') {
                $error = true;
                break;
            }
        }
        
        if (!$error && !in_array('error', $resultados)) {
            ?>
            <form id="redirectForm" method="POST" action="index.php">
                <input type="hidden" name="opcion" value="mostrar_<?php echo htmlspecialchars($redirect_tabla); ?>">
                <input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado_redirect); ?>">
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
                    text: 'Ocurrió un error al activar uno o más registros.'
                });
            </script>
            <?php
        }
        
    } catch (Exception $e) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en el proceso: <?php echo htmlspecialchars($e->getMessage()); ?>'
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
            text: 'Método no permitido.'
        });
    </script>
    <?php
}
?>