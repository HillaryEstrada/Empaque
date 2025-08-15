<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php 
            if (isset($_POST['pk_revision']) && isset($_POST['pk_lote']) && isset($_POST['pk_clasificacion'])) {
                echo "Editar Calidad Completa";
            } else {
                echo "Editar Datos de Calidad";
            }
            ?>
        </h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de calidad completa
        if (isset($_POST['pk_revision']) && isset($_POST['pk_lote']) && isset($_POST['pk_clasificacion'])) {
            // Edición de calidad completa - AQUÍ SE CARGAN TODOS LOS CAMPOS
            echo '<input type="hidden" name="pk_revision" value="' . htmlspecialchars($_POST['pk_revision']) . '" />';
            echo '<input type="hidden" name="pk_lote" value="' . htmlspecialchars($_POST['pk_lote']) . '" />';
            echo '<input type="hidden" name="pk_clasificacion" value="' . htmlspecialchars($_POST['pk_clasificacion']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_calidad_completa" />';
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorCalidad();
            $editar->editarCalidadCompletaControlador();
            
        } elseif (isset($_POST['pk'])) {
            // Edición solo de revisión de calidad (compatibilidad)
            echo '<input type="hidden" name="pk_revision" value="' . htmlspecialchars($_POST['pk']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_revision_calidad" />';
            
            // Mostrar formulario simple
            ?>
            <div class="row">
                <div class="col-12">
                    <h5 class="text-primary">Revisión de Calidad</h5>
                    <?php
                    $editar = new ControladorCalidad();
                    $editar->editarRevisionCalidadControlador();
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_calidad" />

    </form>
</div>

<!-- Script para validación en tiempo real -->
<script>
// Función para validar campos numéricos
function validarCamposNumericos() {
    const primera = document.querySelector('input[name="primera_calidad"]');
    const segunda = document.querySelector('input[name="segunda_calidad"]');
    const descarte = document.querySelector('input[name="descarte"]');
    
    if (primera && segunda && descarte) {
        function validar() {
            if (primera.value < 0 || segunda.value < 0 || descarte.value < 0) {
                primera.setCustomValidity('Los valores no pueden ser negativos');
            } else {
                primera.setCustomValidity('');
                segunda.setCustomValidity('');
                descarte.setCustomValidity('');
            }
        }
        
        primera.addEventListener('input', validar);
        segunda.addEventListener('input', validar);
        descarte.addEventListener('input', validar);
        
        // Validación final en el submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (primera.value < 0 || segunda.value < 0 || descarte.value < 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Los valores no pueden ser negativos'
                });
                return false;
            }
            
            const numeroLote = document.querySelector('input[name="numero_lote"]');
            const uso = document.querySelector('input[name="uso"]');
            
            if (numeroLote && !numeroLote.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El número de lote es requerido'
                });
                return false;
            }
            
            if (uso && !uso.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El uso es requerido'
                });
                return false;
            }
        });
    }
}

// Ejecutar validación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', validarCamposNumericos);
</script>

</div>

<?php 
// Procesar la actualización según el tipo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorCalidad();
    
    switch ($_POST['accion']) {
        case 'actualizar_calidad_completa':
            $registro->actualizarCalidadCompletaControlador();
            break;
            
        case 'actualizar_revision_calidad':
            $registro->actualizarRevisionCalidadControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_revision'], $_POST['pk_lote'], $_POST['pk_clasificacion'])) {
                $registro->actualizarCalidadCompletaControlador();
            } elseif (isset($_POST['pk_revision'])) {
                $registro->actualizarRevisionCalidadControlador();
            }
            break;
    }
}
?>