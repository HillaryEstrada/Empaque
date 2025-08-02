<?php

    class ControladorProductor
    {
        // ALTA PRODUCTOR
        static public function registroProductoControlador() {
            if(isset($_POST["nombre"])) {
                $datosControlador = array(
                    "nombre" => $_POST['nombre'],
                    "telefono" => $_POST['telefono']
                );
        
                $respuesta = ModeloProductor::registroProductorModelo($datosControlador, "productor");
        
                if ($respuesta == 'ok') {
                    echo '<script>
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Se guardaron los datos",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
                } else {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ocurrió un error inesperado"
                        });
                    </script>';
                }
            }
        }

         // Mostrar los datos de los productores
        static public function mostrarProductorControlador($estado)
        {
            $respuesta = ModeloProductor::mostrarProductorModelo($estado);
        
            foreach ($respuesta as $renglon => $valores) {
                ?>
                <tr>
                    <td><?php echo $valores['nombre']; ?></td>
                    <td><?php echo $valores['telefono']; ?></td>
                    <td>
                        <?php if ($estado == 1): ?>
                            <button 
                                    class="btn btn-warning"
                                    onclick="postToExternalSite('index.php', { 
                                        opcion: 'editar_productor', 
                                        pk: '<?php echo htmlspecialchars($valores['pk_productor']); ?>', 
                                        tabla: 'productor', 
                                        pkname: 'pk_productor'
                                    });">
                                    <i class="fa-solid fa-pencil"></i>Editar
                            </button>
                            <!-- Si están activos (estado 1), mostramos el botón de desactivar -->
                            <button 
                                class="btn btn-danger"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'desactivar', 
                                    pk: '<?php echo htmlspecialchars($valores['pk_productor']); ?>', 
                                    tabla: 'productor', 
                                    pkname: 'pk_productor',
                                    // estado: '1' // Mantiene la vista de activos  // se puede dejar asi, ya se maneja en el desactivar la carga
                                });">
                                <i class="fa-solid fa-trash"></i> Desactivar 
                            </button>
                        <?php else: ?>
                            <!-- Si están inactivos (estado 0), mostramos el botón de activar -->
                            <button 
                                class="btn btn-primary"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'activar', 
                                    pk: '<?php echo htmlspecialchars($valores['pk_productor']); ?>', 
                                    tabla: 'productor', 
                                    pkname: 'pk_productor',
                                    // estado: '0' // Mantiene la vista de inactivos  se puede usar esto o lo de abajo
                                        estado: '<?php echo $estado; ?>' // Mantiene la vista actual
                                });">
                                <i class="fa-solid fa-check"></i> Activar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
        }

        // Obtener la informacion para poder actualizar los datos de los productores
        static public function editarProductorControlador()
        {
            if (isset($_POST['pk'])) {
                $pk = $_POST['pk'];
                $tabla = "productor";
            
                $respuesta = ModeloProductor::editarProductorModelo($pk, $tabla);
            
                foreach ($respuesta as $renglon => $valor) {
                ?>
                    <!-- Nombre del usuario -->
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" required="true"
                        value="<?php echo $valor['nombre']; ?>">
                    </div>
                    <!-- Telefono del usuario -->
                    <div class="mb-3">
                        <label class="form-label">Telefono:</label>
                        <input type="text" class="form-control" name="telefono" required="true"
                        value="<?php echo $valor['telefono']; ?>">
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <a href="index.php?opcion=mostrar_productor" class="btn btn-danger">Salir</a>
                    </div>
                <?php
                }
            } 
        }

// Actualizar los datos de los usuarios
static public function actualizarProductorControlador()
{
    if(isset($_POST["nombre"], $_POST["telefono"], $_POST["pk_productor"]))
    {   
        $datosControlador = array(
            "nombre" => $_POST['nombre'],
            "telefono" => $_POST['telefono'],
            "pk_productor" => $_POST['pk_productor']
        );

        $respuesta = ModeloProductor::actualizacionProductorModelo($datosControlador, "productor");

        if($respuesta == 'ok')
        {
            ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Datos actualizados!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Redirección interna con POST oculto
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'opcion';
                    input.value = 'mostrar_productor';
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                });
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ooops...',
                    text: 'Ocurrió un error'
                });
            </script>
            <?php
        }
    }
}

    }
?>