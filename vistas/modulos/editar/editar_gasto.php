<?php
// editar_gasto.php
?>
<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php 
            if (isset($_POST['pk_gasto']) && (isset($_POST['pk_gasto_llegada']) || isset($_POST['pk_gasto_salida']))) {
                echo "Editar Gasto Completo";
            } else {
                echo "Editar Datos de Gasto";
            }
            ?>
        </h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de gasto completo
        if (isset($_POST['pk_gasto']) && (isset($_POST['pk_gasto_llegada']) || isset($_POST['pk_gasto_salida']))) {
            // Edición de gasto completo - AQUÍ SE CARGAN TODOS LOS CAMPOS
            echo '<input type="hidden" name="pk_gasto" value="' . htmlspecialchars($_POST['pk_gasto']) . '" />';
            
            if (isset($_POST['pk_gasto_llegada'])) {
                echo '<input type="hidden" name="pk_gasto_llegada" value="' . htmlspecialchars($_POST['pk_gasto_llegada']) . '" />';
                echo '<input type="hidden" name="accion" value="actualizar_gasto_llegada" />';
            } else {
                echo '<input type="hidden" name="pk_gasto_salida" value="' . htmlspecialchars($_POST['pk_gasto_salida']) . '" />';
                if (isset($_POST['pk_venta'])) {
                    echo '<input type="hidden" name="pk_venta" value="' . htmlspecialchars($_POST['pk_venta']) . '" />';
                }
                echo '<input type="hidden" name="accion" value="actualizar_gasto_salida" />';
            }
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorGasto();
            $editar->editarGastoCompletoControlador();
            
        } elseif (isset($_POST['pk'])) {
            // Edición solo de datos básicos del gasto (compatibilidad)
            echo '<input type="hidden" name="pk_gasto" value="' . htmlspecialchars($_POST['pk']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_gasto_basico" />';
            
            // Mostrar formulario simple
            ?>
            <div class="row">
                <div class="col-12">
                    <h5 class="text-primary">Datos del Gasto</h5>
                    <?php
                    $editar = new ControladorGasto();
                    $editar->editarGastoBasicoControlador();
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_gasto" />

    </form>
</div>

<!-- Script para validación en tiempo real -->
<script>
// Función para mostrar/ocultar campos según el tipo
function mostrarCamposPorTipo() {
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const camposLlegada = document.getElementById('campos-llegada-edit');
    const camposSalida = document.getElementById('campos-salida-edit');
    const camposVenta = document.getElementById('campos-venta-edit');
    
    if (tipoRadios.length > 0) {
        tipoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'llegada') {
                    if (camposLlegada) camposLlegada.style.display = 'block';
                    if (camposSalida) camposSalida.style.display = 'none';
                    if (camposVenta) camposVenta.style.display = 'none';
                    
                    // Hacer campos requeridos/opcionales
                    const loteSelect = document.querySelector('select[name="fk_lote"]');
                    const montoLlegada = document.querySelector('input[name="monto_llegada"]');
                    const salidaSelect = document.querySelector('select[name="fk_salida"]');
                    const montoSalida = document.querySelector('input[name="monto_salida"]');
                    
                    if (loteSelect) loteSelect.required = true;
                    if (montoLlegada) montoLlegada.required = true;
                    if (salidaSelect) salidaSelect.required = false;
                    if (montoSalida) montoSalida.required = false;
                    
                } else if (this.value === 'salida') {
                    if (camposLlegada) camposLlegada.style.display = 'none';
                    if (camposSalida) camposSalida.style.display = 'block';
                    if (camposVenta) camposVenta.style.display = 'block';
                    
                    // Hacer campos requeridos/opcionales
                    const loteSelect = document.querySelector('select[name="fk_lote"]');
                    const montoLlegada = document.querySelector('input[name="monto_llegada"]');
                    const salidaSelect = document.querySelector('select[name="fk_salida"]');
                    const montoSalida = document.querySelector('input[name="monto_salida"]');
                    
                    if (loteSelect) loteSelect.required = false;
                    if (montoLlegada) montoLlegada.required = false;
                    if (salidaSelect) salidaSelect.required = true;
                    if (montoSalida) montoSalida.required = true;
                }
            });
        });
        
        // Disparar el evento change en el radio button seleccionado
        const selectedRadio = document.querySelector('input[name="tipo"]:checked');
        if (selectedRadio) {
            selectedRadio.dispatchEvent(new Event('change'));
        }
    }
}

// Función para validar el formulario
function validarFormulario() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const tipo = document.querySelector('input[name="tipo"]:checked');
            
            if (!tipo) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un tipo de gasto'
                });
                return false;
            }
            
            if (tipo.value === 'llegada') {
                const lote = document.querySelector('select[name="fk_lote"]');
                const monto = document.querySelector('input[name="monto_llegada"]');
                
                if (lote && monto && (!lote.value || !monto.value)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe completar todos los campos requeridos para gasto de llegada'
                    });
                    return false;
                }
            } else if (tipo.value === 'salida') {
                const salida = document.querySelector('select[name="fk_salida"]');
                const monto = document.querySelector('input[name="monto_salida"]');
                
                if (salida && monto && (!salida.value || !monto.value)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe completar todos los campos requeridos para gasto de salida'
                    });
                    return false;
                }
            }
        });
    }
}

// Ejecutar funciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    mostrarCamposPorTipo();
    validarFormulario();
});
</script>

</div>

<?php 
// Procesar la actualización según el tipo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorGasto();
    
    switch ($_POST['accion']) {
        case 'actualizar_gasto_llegada':
            $registro->actualizarGastoLlegadaControlador();
            break;
            
        case 'actualizar_gasto_salida':
            $registro->actualizarGastoSalidaControlador();
            break;
            
        case 'actualizar_gasto_basico':
            $registro->actualizarGastoBasicoControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_gasto']) && isset($_POST['pk_gasto_llegada'])) {
                $registro->actualizarGastoLlegadaControlador();
            } elseif (isset($_POST['pk_gasto']) && isset($_POST['pk_gasto_salida'])) {
                $registro->actualizarGastoSalidaControlador();
            } elseif (isset($_POST['pk_gasto'])) {
                $registro->actualizarGastoBasicoControlador();
            }
            break;
    }
}
?>