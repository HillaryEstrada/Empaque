<?php
// mostrar_gasto.php
?>
<div class="mango-container"> 
    <div align="center">

        <!-- Mostrar la alerta SweetAlert si existe -->
        <?php if (isset($_POST['alerta'])): ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?php echo ($_POST['alerta'] === 'activado') ? "Elemento activado correctamente" : "Elemento desactivado correctamente"; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        <?php endif; ?>

        <!-- Aquí sí va el encabezado visual de la sección -->
        <div class="alert alert-primary mt-5" role="alert">
            <h1 id="titulo">
                <?php
                    $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
                    echo ($estado == 1) ? "Mostrar Gastos Completos" : "Papelera Gastos Completos";
                ?>
            </h1>
        </div>

    </div>
</div>

<!--Inicio del Proceso de Alta-->

<!-- Formulario de alta de gastos completos (oculto inicialmente) -->   
<div id="formulario-alta" style="display: none;">
    <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
    <div class="row">
        <!-- Datos del gasto -->
        <div class="col-md-6">
            <h5 class="text-primary">Datos del Gasto</h5>
            <div class="form-group mb-3">
                <label class="form-label">Nombre del Gasto:</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del gasto" value="" required />
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" placeholder="Descripción del gasto" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Tipo de Gasto:</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="tipo_llegada" value="llegada" required>
                        <label class="form-check-label" for="tipo_llegada">Llegada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="tipo_salida" value="salida" required>
                        <label class="form-check-label" for="tipo_salida">Salida</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos específicos según el tipo -->
        <div class="col-md-6">
            <h5 class="text-primary">Datos Específicos</h5>
            
            <!-- Campos para gasto de llegada -->
            <div id="campos-llegada" style="display: none;">
                <div class="form-group mb-3">
                    <label class="form-label">Lote:</label>
                    <select name="fk_lote_llegada" class="form-control">
                        <option value="">Seleccionar lote...</option>
                        <?php
                        // Cargar lotes activos
                        $gastos = new ControladorGasto();
                        $gastos->cargarLotesControlador();
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Monto:</label>
                    <input type="number" name="monto_llegada" class="form-control" placeholder="0.00" step="0.01" min="0" />
                </div>
            </div>
            
            <!-- Campos para gasto de salida -->
            <div id="campos-salida" style="display: none;">
                <div class="form-group mb-3">
                    <label class="form-label">Salida:</label>
                    <select name="fk_salida" class="form-control">
                        <option value="">Seleccionar salida...</option>
                        <?php
                        // Cargar salidas activas
                        $gastos->cargarSalidasControlador();
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Monto:</label>
                    <input type="number" name="monto_salida" class="form-control" placeholder="0.00" step="0.01" min="0" />
                </div>
            </div>
            
            <!-- Campos para venta (cuando el tipo es salida) -->
            <div id="campos-venta" style="display: none;">
                <h6 class="text-secondary mt-3">Datos de Venta (Opcional)</h6>
                <div class="form-group mb-3">
                    <label class="form-label">Ingreso Total:</label>
                    <input type="number" name="ingreso_total" class="form-control" placeholder="0.00" step="0.01" min="0" />
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Observaciones de Venta:</label>
                    <textarea name="observaciones_venta" class="form-control" placeholder="Observaciones de la venta" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-grid gap-2 col-6 mx-auto mt-3">
        <button class="btn btn-primary" type="submit">Guardar Gasto</button>
        <button class="btn btn-danger" type="button" onclick="ocultarFormulario()">Salir</button>
    </div>
    
    <!-- Campos hidden -->
    <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
    </form>
</div>

<!--Fin del Proceso de Alta-->

<!--Inicio Proceso de Mostrar-->

<!-- Tabla de gastos completos -->
<div id="tabla-catalogo" class="w-100">
<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Referencia</th>
            <th>Monto</th>
            <th>Ingreso Total</th>
            <th>
                <?php if ($estado == 1): ?> <!-- Solo muestra el botón si el estado es activo (1) -->
                <button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" data-title="Alta gasto completo">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear Gasto
                </button>
                <?php endif; ?>
                
                <!-- Botón para alternar entre activos e inactivos -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="opcion" value="mostrar_gasto">
                        <input type="hidden" name="estado" value="<?php echo ($estado == 1) ? 0 : 1; ?>">
                        <button type="submit" class="btn btn-sm <?php echo ($estado == 1) ? 'btn-warning' : 'btn-primary'; ?>">
                            <i class="fa-solid <?php echo ($estado == 1) ? 'fa-archive' : 'fa-undo'; ?>"></i>
                            <?php echo ($estado == 1) ? 'Ver Inactivos' : 'Ver Activos'; ?>
                        </button> 
                    </form>
            </th>
        </tr>
    </thead>

    <!-- Esto consulta en el controlador el tipo de estado -->
    <tbody id="tabla-body">
        <?php
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
            $gasto = new ControladorGasto();
            $gasto->mostrarGastosCompletosControlador($estado);
        ?>
    </tbody>
</table>
<!--Fin del Proceso de Mostrar-->

        <!-- Contenedor de paginación con contador de registros -->
        <div id="paginacion-container" class="pagination-container text-center mt-3">
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-primary me-2" id="btn-primero" disabled>Primero</button>
                <button class="btn btn-primary me-2" id="btn-anterior" disabled>Anterior</button>
                <span id="pagina-info" class="mx-3">Página 1</span>
                <input type="text" id="pagina-input" value="1" style="width: 40px; text-align: center;">
                <span id="registro-info" class="mx-3"></span> <!-- Contador de registros -->
                <button class="btn btn-primary ms-2" id="btn-siguiente">Siguiente</button>
                <button class="btn btn-primary ms-2" id="btn-ultimo">Último</button>
            </div>
        </div>

    </div>

</div>

<!-- Script para mostrar/ocultar campos según el tipo -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const camposLlegada = document.getElementById('campos-llegada');
    const camposSalida = document.getElementById('campos-salida');
    const camposVenta = document.getElementById('campos-venta');
    
    tipoRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'llegada') {
                camposLlegada.style.display = 'block';
                camposSalida.style.display = 'none';
                camposVenta.style.display = 'none';
                
                // Hacer campos requeridos/opcionales
                document.querySelector('select[name="fk_lote_llegada"]').required = true;
                document.querySelector('input[name="monto_llegada"]').required = true;
                document.querySelector('select[name="fk_salida"]').required = false;
                document.querySelector('input[name="monto_salida"]').required = false;
                
            } else if (this.value === 'salida') {
                camposLlegada.style.display = 'none';
                camposSalida.style.display = 'block';
                camposVenta.style.display = 'block';
                
                // Hacer campos requeridos/opcionales
                document.querySelector('select[name="fk_lote_llegada"]').required = false;
                document.querySelector('input[name="monto_llegada"]').required = false;
                document.querySelector('select[name="fk_salida"]').required = true;
                document.querySelector('input[name="monto_salida"]').required = true;
            }
        });
    });
});

// Validación del formulario
document.getElementById('form-alta').addEventListener('submit', function(e) {
    var tipo = document.querySelector('input[name="tipo"]:checked');
    
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
        var lote = document.querySelector('select[name="fk_lote_llegada"]').value;
        var monto = document.querySelector('input[name="monto_llegada"]').value;
        
        if (!lote || !monto) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe completar todos los campos requeridos para gasto de llegada'
            });
            return false;
        }
    } else if (tipo.value === 'salida') {
        var salida = document.querySelector('select[name="fk_salida"]').value;
        var monto = document.querySelector('input[name="monto_salida"]').value;
        
        if (!salida || !monto) {
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
</script>

 <!-- Esto se encarga de mandar los datos de entrada al controlador -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el formulario tiene los datos necesarios para gasto completo
    if (isset($_POST['nombre']) && !empty($_POST['tipo'])) {
        
        // Recoger datos del formulario
        $nombre = htmlspecialchars($_POST['nombre']);
        $descripcion = htmlspecialchars($_POST['descripcion']); 
        $tipo = htmlspecialchars($_POST['tipo']); 

        // Llamar al controlador para manejar el registro completo
        $registro = new ControladorGasto();
        $registro->registroGastoCompletoControlador($nombre, $descripcion, $tipo);
    }
}
?>