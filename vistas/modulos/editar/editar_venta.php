<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php 
            if (isset($_POST['pk_salida']) && isset($_POST['pk_venta'])) {
                echo "Editar Venta Completa";
            } else {
                echo "Editar Venta";
            }
            ?>
        </h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de venta completa
        if (isset($_POST['pk_salida']) && isset($_POST['pk_venta'])) {
            // Edición de venta completa - AQUÍ SE CARGAN TODOS LOS CAMPOS
            echo '<input type="hidden" name="pk_salida" value="' . htmlspecialchars($_POST['pk_salida']) . '" />';
            echo '<input type="hidden" name="pk_venta" value="' . htmlspecialchars($_POST['pk_venta']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_venta_completa" />';
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorVenta();
            $editar->editarVentaCompletaControlador();
            
        } elseif (isset($_POST['pk'])) {
            // Edición solo de venta básica (compatibilidad)
            echo '<input type="hidden" name="pk_venta" value="' . htmlspecialchars($_POST['pk']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_venta_basica" />';
            
            // Mostrar formulario simple
            ?>
            <div class="row">
                <div class="col-12">
                    <h5 class="text-primary">Datos de la Venta</h5>
                    <?php
                    $editar = new ControladorVenta();
                    $editar->editarVentaBasicaControlador();
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_venta" />

    </form>
</div>

</div>

<?php 
// Procesar la actualización según el tipo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorVenta();
    
    switch ($_POST['accion']) {
        case 'actualizar_venta_completa':
            $registro->actualizarVentaCompletaControlador();
            break;
            
        case 'actualizar_venta_basica':
            $registro->actualizarVentaBasicaControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_salida'], $_POST['pk_venta'])) {
                $registro->actualizarVentaCompletaControlador();
            } elseif (isset($_POST['pk_venta'])) {
                $registro->actualizarVentaBasicaControlador();
            }
            break;
    }
}
?>