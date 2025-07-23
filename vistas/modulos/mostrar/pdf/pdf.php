<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php
                $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
                echo ($estado == 1) ? "Analisis de documentos" : "Papelera Datos de Usuarios";
            ?>
        </h1>
    </div>
</div>
<br>


<!--Inicio del Proceso de Alta-->

<!-- Formulario de alta de datos de usuarios (oculto inicialmente) -->   
<div id="formulario-alta" style="display: none;">
    <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
    <div class="row">
        <div class="form-group mb-3">
            <label class="form-label">Nombres:</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre del Usuario" value="" required />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Apellidos:</label>
            <input type="text" name="apellidos" class="form-control" placeholder="Apellidos del Usuario" value="" required />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Edad:</label>
            <input type="text" name="edad" class="form-control" placeholder="Edad del Usuario" value="" required />
        </div>
        <div class="form-group mb-3">
    <label class="form-label">Sexo:</label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_m" value="M" required>
            <label class="form-check-label" for="sexo_m">Masculino</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sexo" id="sexo_f" value="F" required>
            <label class="form-check-label" for="sexo_f">Femenino</label>
        </div>
    </div>
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
        <input type="hidden" name="menu" value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>" />
    </div>
    <div class="form-group mb-3">
        <input type="hidden" name="menu" value="<?php echo isset($_POST['edad']) ? htmlspecialchars($_POST['edad']) : ''; ?>" />
    </div>
    <div class="form-group mb-3">
        <input type="hidden" name="menu" value="<?php echo isset($_POST['sexo']) ? htmlspecialchars($_POST['sexo']) : ''; ?>" />
    </div>
    <div class="form-group mb-3">
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
    </div>
    </form>
</div>

<!--Fin del Proceso de Alta-->

<!--Inicio Proceso de Mostrar-->

<!-- Tabla de datos de usuarios -->
<div id="tabla-catalogo" class="container">
<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>
                <?php if ($estado == 1): ?> <!-- Solo muestra el botón si el estado es activo (1) -->
                <button class="btn btn-success" onclick="mostrarFormulario(this)" data-title="Alta datos usuarios">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear
                </button>
                <?php endif; ?>
                
                <!-- Botón para alternar entre activos e inactivos -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="opcion" value="mostrar_dato_usuario">
                        <input type="hidden" name="estado" value="<?php echo ($estado == 1) ? 0 : 1; ?>">
                        <button type="submit" class="btn <?php echo ($estado == 1) ? 'btn-secondary' : 'btn-primary'; ?>">
                            <?php echo ($estado == 1) ? 'Ir a Inactivos' : 'Volver Activos'; ?>
                        </button> 
                    </form>
            </th>
        </tr>
    </thead>

    <!-- Esto consulta en el controlador el tipo de estado  esto se queda asi, solo cambia el direccionamiento a la funcion que vas a ir en el controlador...-->
    <tbody id="tabla-body">
        <?php
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
            $categoria = new ControladorUsuario();
            $categoria->mostrarDatosPersonasControlador($estado);  // Aqui es donde se cambia...
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

 
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el formulario tiene los datos necesarios
    if (isset($_POST['nombre']) && !empty($_POST['apellidos']) && !empty($_POST['edad']) && !empty($_POST['sexo'])) {
        // Recoger datos del formulario
        $nombre = htmlspecialchars($_POST['nombre']); // Sanitizar entrada para mayor seguridad
        $apellidos = htmlspecialchars($_POST['apellidos']); 
        $edad = htmlspecialchars($_POST['edad']); 
        $sexo = htmlspecialchars($_POST['sexo']); 

        // Llamar al controlador para manejar el registro
        $registro = new ControladorUsuario();
        $registro->registroPersonaControlador($nombre, $apellidos, $edad, $sexo);
    
    }
}
?>