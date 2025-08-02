<?php

    class ControladorUsuario
    {
        // ALTA PERSONA
        static public function registroPersonaControlador() {
            if(isset($_POST["nombre"])) {
                $datosControlador = array(
                    "nombre" => $_POST['nombre'],
                    "apellidos" => $_POST['apellidos'],
                    "edad" => $_POST['edad'],
                    "sexo" => $_POST['sexo']
                );
        
                $respuesta = ModeloUsuario::registroPersonaModelo($datosControlador, "dato_usuario");
        
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

        // Mostrar los datos de las personas
        static public function mostrarDatosPersonasControlador($estado)
        {
            $respuesta = ModeloUsuario::mostrarDatosPersonasModelo($estado);
        
            foreach ($respuesta as $renglon => $valores) {
                ?>
                <tr>
                    <td><?php echo $valores['nombre']; ?></td>
                    <td><?php echo $valores['apellidos']; ?></td>
                    <td><?php echo $valores['edad']; ?></td>
                    <td><?php echo $valores['sexo']; ?></td>
                    <td>
                        <?php if ($estado == 1): ?>
                            <button 
                                    class="btn btn-warning"
                                    onclick="postToExternalSite('index.php', { 
                                        opcion: 'editar_dato_usuario', 
                                        pk: '<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>', 
                                        tabla: 'dato_usuario', 
                                        pkname: 'pk_dato_usuario'
                                    });">
                                    <i class="fa-solid fa-pencil"></i>Editar
                            </button>
                            <!-- Si están activos (estado 1), mostramos el botón de desactivar -->
                            <button 
                                class="btn btn-danger"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'desactivar', 
                                    pk: '<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>', 
                                    tabla: 'dato_usuario', 
                                    pkname: 'pk_dato_usuario',
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
                                    pk: '<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>', 
                                    tabla: 'dato_usuario', 
                                    pkname: 'pk_dato_usuario',
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

        // Obtener la informacion para poder actualizar los datos de los usuarios
        static public function editarDatosUsuariosControlador()
        {
            if (isset($_POST['pk'])) {
                $pk = $_POST['pk'];
                $tabla = "dato_usuario";
            
                $respuesta = ModeloUsuario::editarDatosUsuariosModelo($pk, $tabla);
            
                foreach ($respuesta as $renglon => $valor) {
                ?>
                    <!-- Nombre del usuario -->
                    <div class="mb-3">
                        <label class="form-label">Nombres:</label>
                        <input type="text" class="form-control" name="nombre" required="true"
                        value="<?php echo $valor['nombre']; ?>">
                    </div>
                    <!-- Apellidos del usuario -->
                    <div class="mb-3">
                        <label class="form-label">Apellidos:</label>
                        <input type="text" class="form-control" name="apellidos" required="true"
                        value="<?php echo $valor['apellidos']; ?>">
                    </div>
                    <!-- Edad del usuario -->
                    <div class="mb-3">
                        <label class="form-label">Edad:</label>
                        <input type="text" class="form-control" name="edad" required="true"
                        value="<?php echo $valor['edad']; ?>">
                    </div>
                    <!-- Sexo del usuario -->
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexo_m" value="M" 
                                <?php echo ($valor['sexo'] == 'M') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="sexo_m">Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexo_f" value="F" 
                                <?php echo ($valor['sexo'] == 'F') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="sexo_f">Femenino</label>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        <a href="index.php?opcion=mostrar_dato_usuario" class="btn btn-danger">Salir</a>
                    </div>
                <?php
                }
            } 
        }

        // Actrualizar los datos de los usuarios
        static public function actualizarDatosUsuariosControlador()
{
    if (
        isset($_POST["nombre"], $_POST["apellidos"], $_POST["edad"], $_POST["sexo"], $_POST["pk_dato_usuario"])
    ) {
        $datosControlador = array(
            "nombre" => $_POST['nombre'],
            "apellidos" => $_POST['apellidos'],
            "edad" => $_POST['edad'],
            "sexo" => $_POST['sexo'],
            "pk_dato_usuario" => $_POST['pk_dato_usuario']
        );

        $respuesta = ModeloUsuario::actualizacionDatosUsuariosModelo($datosControlador, "dato_usuario");

        if ($respuesta == 'ok') {
            ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Datos actualizados!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Crear formulario oculto para forzar POST a mostrar_dato_usuario
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'opcion';
                    input.value = 'mostrar_dato_usuario';

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });
            </script>
            <?php
        } else {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ooops...',
                    text: 'Ocurrió un error al actualizar'
                });
            </script>
            <?php
        }
    }
}

    }
?>