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
                    echo ($estado == 1) ? "Mostrar Datos de Roles" : "Papelera Datos de Roles";
                ?>
            </h1>
        </div>

    </div>
</div>
<!--Inicio del Proceso de Alta-->

<!-- Formulario de alta de productor (oculto inicialmente) -->   
<div id="formulario-alta" style="display: none;">
    <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
    <div class="row">
        <div class="form-group mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre del productor" value="" required />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Telefono:</label>
            <input type="text" name="telefono" class="form-control" placeholder="(555-3333)" value="" required />
        </div>
    </div>
    <div class="d-grid gap-2 col-6 mx-auto">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <button class="btn btn-danger" type="button" onclick="ocultarFormulario()">Salir</button>
    </div>
    
    <!-- Campos hidden: Solo se llenan después del envío del formulario -->
    <div class="form-group mb-3">
        <input type="hidden" name="menu" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" />
    </div>
    <div class="form-group mb-3">
        <input type="hidden" name="menu" value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" />
    </div>
    <div class="form-group mb-3">
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
    </div>
    </form>
</div>

<!--Fin del Proceso de Alta-->

<!--Inicio Proceso de Mostrar-->

<!-- Tabla de productor -->
<div id="tabla-catalogo" class="w-100">
<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>
                <?php if ($estado == 1): ?> <!-- Solo muestra el botón si el estado es activo (1) -->
                <button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" data-title="Alta Productor">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear Productor
                </button>
                <?php endif; ?>
                 <!-- Botón para alternar entre activos e inactivos -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="opcion" value="mostrar_productor">
                        <input type="hidden" name="estado" value="<?php echo ($estado == 1) ? 0 : 1; ?>">
                        <button type="submit" class="btn btn-sm <?php echo ($estado == 1) ? 'btn-warning' : 'btn-primary'; ?>">
                            <i class="fa-solid <?php echo ($estado == 1) ? 'fa-archive' : 'fa-undo'; ?>"></i>
                            <?php echo ($estado == 1) ? 'Ver Inactivos' : 'Ver Activos'; ?>
                        </button> 
                    </form>
            </th>
        </tr>
    </thead>

    <!-- Esto consulta en el controlador el tipo de estado  esto se queda asi, solo cambia el direccionamiento a la funcion que vas a ir en el controlador...-->
    <tbody id="tabla-body">
        <?php
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
            $categoria = new ControladorProductor();
            $categoria->mostrarProductorControlador($estado);  // Aqui es donde se cambia...
        ?>
    </tbody>
</table>
<!--Fin del Proceso de Mostrar-->

        <!-- Contenedor de paginación con contador de registros -->
        <div id="paginacion-container" class="pagination-container text-center mt-4">
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-primary btn-sm me-2" id="btn-primero" disabled>
                    <i class="fa-solid fa-angles-left"></i> Primero
                </button>
                <button class="btn btn-primary btn-sm me-2" id="btn-anterior" disabled>
                    <i class="fa-solid fa-angle-left"></i> Anterior
                </button>
                <span id="pagina-info" class="mx-3 badge badge-activo">Página 1</span>
                <input type="text" id="pagina-input" value="1" class="form-control" style="width: 60px; text-align: center; display: inline-block;">
                <span id="registro-info" class="mx-3 badge badge-inactivo"></span> <!-- Contador de registros -->
                <button class="btn btn-primary btn-sm ms-2" id="btn-siguiente">
                    Siguiente <i class="fa-solid fa-angle-right"></i>
                </button>
                <button class="btn btn-primary btn-sm ms-2" id="btn-ultimo">
                    Último <i class="fa-solid fa-angles-right"></i>
                </button>
            </div>
        </div>

    </div>

<div class="section-footer">
    <p>Sistema de Gestión - Empacadora de Mango 🥭</p>
</div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el formulario tiene los datos necesarios
    if (isset($_POST['nombre']) && !empty($_POST['telefono'])) {
        // Recoger datos del formulario
        $nombre = htmlspecialchars($_POST['nombre']); // Sanitizar entrada para mayor seguridad
        $telefono = htmlspecialchars($_POST['telefono']); 

        // Llamar al controlador para manejar el registro
        $registro = new ControladorProductor();
        $registro->registroProductoControlador($nombre, $telefono);
    
    }
}
?>