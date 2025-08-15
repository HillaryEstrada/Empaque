<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php 
            if (isset($_POST['pk_gasto']) && 
                ((isset($_POST['pk_gasto_llegada']) && $_POST['tipo'] === 'llegada') || 
                 (isset($_POST['pk_gasto_salida']) && $_POST['tipo'] === 'salida'))) {
                echo "Editar Gasto Completo";
            } else {
                echo "Editar Gasto";
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
        if (isset($_POST['pk_gasto']) && isset($_POST['tipo'])) {
            
            // Campos hidden para identificar el tipo y las claves
            echo '<input type="hidden" name="pk_gasto" value="' . htmlspecialchars($_POST['pk_gasto']) . '" />';
            echo '<input type="hidden" name="tipo_original" value="' . htmlspecialchars($_POST['tipo']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_gasto_completo" />';
            
            if ($_POST['tipo'] === 'llegada' && isset($_POST['pk_gasto_llegada'])) {
                echo '<input type="hidden" name="pk_gasto_llegada" value="' . htmlspecialchars($_POST['pk_gasto_llegada']) . '" />';
            } elseif ($_POST['tipo'] === 'salida' && isset($_POST['pk_gasto_salida'])) {
                echo '<input type="hidden" name="pk_gasto_salida" value="' . htmlspecialchars($_POST['pk_gasto_salida']) . '" />';
            }
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorGasto();
            $editar->editarGastoCompletoControlador();
            
        } elseif (isset($_POST['pk'])) {
            // Edición solo de gasto básico (compatibilidad)
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

<!-- Script para manejo dinámico de campos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const campoLote = document.getElementById('campo-lote');
    const campoSalida = document.getElementById('campo-salida');
    const fkLote = document.querySelector('select[name="fk_lote"]');
    const fkSalida = document.querySelector('select[name="fk_salida"]');
    
    function toggleCampos() {
        const tipoSeleccionado = document.querySelector('input[name="tipo"]:checked');
        if (tipoSeleccionado) {
            if (tipoSeleccionado.value === 'llegada') {
                if (campoLote) campoLote.style.display = 'block';
                if (campoSalida) campoSalida.style.display = 'none';
                if (fkLote) fkLote.required = true;
                if (fkSalida) {
                    fkSalida.required = false;
                    fkSalida.value = '';
                }
            } else if (tipoSeleccionado.value === 'salida') {
                if (campoLote) campoLote.style.display = 'none';
                if (campoSalida) campoSalida.style.display = 'block';
                if (fkLote) {
                    fkLote.required = false;
                    fkLote.value = '';
                }
                if (fkSalida) fkSalida.required = true;
            }
        }
    }
    
    // Ejecutar al cargar la página
    toggleCampos();
    
    // Escuchar cambios en los radio buttons
    tipoRadios.forEach(radio => {
        radio.addEventListener('change', toggleCampos);
    });
});
</script>

</div>

<?php 
// Procesar la actualización según el tipo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorGasto();
    
    switch ($_POST['accion']) {
        case 'actualizar_gasto_completo':
            $registro->actualizarGastoCompletoControlador();
            break;
            
        case 'actualizar_gasto_basico':
            $registro->actualizarGastoBasicoControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_gasto']) && isset($_POST['tipo_original'])) {
                $registro->actualizarGastoCompletoControlador();
            } elseif (isset($_POST['pk_gasto'])) {
                $registro->actualizarGastoBasicoControlador();
            }
            break;
    }
}
?>