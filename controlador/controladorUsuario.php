<?php

class ControladorUsuario
{
    // ALTA USUARIO COMPLETO (dato_usuario + usuario)
    static public function registroUsuarioCompletoControlador($nombre, $apellidos, $edad, $sexo, $usuario, $contrasena, $fk_rol) {
        // Verificar que el usuario no exista
        $verificarUsuario = ModeloUsuario::verificarUsuarioExistenteModelo($usuario);
        
        if ($verificarUsuario) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "El nombre de usuario ya existe"
                });
            </script>';
            return;
        }
        
        $datosPersona = array(
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "edad" => $edad,
            "sexo" => $sexo
        );
        
        // Encriptar contraseña
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
        
        $datosUsuario = array(
            "usuario" => $usuario,
            "contrasena" => $contrasenaHash,
            "fk_rol" => $fk_rol
        );

        $respuesta = ModeloUsuario::registroUsuarioCompletoModelo($datosPersona, $datosUsuario);

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Usuario creado exitosamente",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "' . $respuesta . '"
                });
            </script>';
        }
    }

    // Cargar roles activos para el select
    static public function cargarRolesControlador() {
        $respuesta = ModeloUsuario::cargarRolesModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_rol'] . '">' . $valores['nombre'] . '</option>';
        }
    }

    // Mostrar usuarios completos (dato_usuario + usuario + rol)
    static public function mostrarUsuariosCompletosControlador($estado)
    {
        $respuesta = ModeloUsuario::mostrarUsuariosCompletosModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td><?php echo $valores['nombre']; ?></td>
                <td><?php echo $valores['apellidos']; ?></td>
                <td><?php echo $valores['edad']; ?></td>
                <td><?php echo ($valores['sexo'] == 'M') ? 'Masculino' : (($valores['sexo'] == 'F') ? 'Femenino' : $valores['sexo']); ?></td>
                <td><?php echo $valores['usuario']; ?></td>
                <td><?php echo $valores['rol_nombre']; ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_dato_usuario', 
                                    pk_usuario: '<?php echo htmlspecialchars($valores['pk_usuario']); ?>', 
                                    pk_dato_usuario: '<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>'
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'usuario:<?php echo htmlspecialchars($valores['pk_usuario']); ?>:pk_usuario,dato_usuario:<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>:pk_dato_usuario',
                                redirect_tabla: 'dato_usuario',
                                estado_redirect: '1'
                            });">
                            <i class="fa-solid fa-trash"></i> Desactivar 
                        </button>
                    <?php else: ?>
                        <!-- Botón de Activar usando archivos multitabla -->
                        <button 
                            class="btn btn-primary btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'activar_multitabla',
                                tablas: 'usuario:<?php echo htmlspecialchars($valores['pk_usuario']); ?>:pk_usuario,dato_usuario:<?php echo htmlspecialchars($valores['pk_dato_usuario']); ?>:pk_dato_usuario',
                                redirect_tabla: 'dato_usuario',
                                estado_redirect: '<?php echo $estado; ?>'
                            });">
                            <i class="fa-solid fa-check"></i> Activar
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
        }
    }

    // Obtener información para editar usuario completo
    static public function editarUsuarioCompletoControlador()
    {
        if (isset($_POST['pk_usuario']) && isset($_POST['pk_dato_usuario'])) {
            $pk_usuario = $_POST['pk_usuario'];
            $pk_dato_usuario = $_POST['pk_dato_usuario'];
        
            $respuesta = ModeloUsuario::editarUsuarioCompletoModelo($pk_usuario, $pk_dato_usuario);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos Personales</h5>
                        <!-- Nombre del usuario -->
                        <div class="mb-3">
                            <label class="form-label">Nombres:</label>
                            <input type="text" class="form-control" name="nombre" required="true"
                            value="<?php echo htmlspecialchars($valor['nombre']); ?>">
                        </div>
                        <!-- Apellidos del usuario -->
                        <div class="mb-3">
                            <label class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" name="apellidos" required="true"
                            value="<?php echo htmlspecialchars($valor['apellidos']); ?>">
                        </div>
                        <!-- Edad del usuario -->
                        <div class="mb-3">
                            <label class="form-label">Edad:</label>
                            <input type="number" class="form-control" name="edad" min="18" max="100" required="true"
                            value="<?php echo htmlspecialchars($valor['edad']); ?>">
                        </div>
                        <!-- Sexo del usuario -->
                        <div class="mb-3">
                            <label class="form-label">Sexo:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_m_edit" value="M" 
                                        <?php echo ($valor['sexo'] == 'M') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="sexo_m_edit">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_f_edit" value="F" 
                                        <?php echo ($valor['sexo'] == 'F') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="sexo_f_edit">Femenino</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de Acceso</h5>
                        <!-- Usuario -->
                        <div class="mb-3">
                            <label class="form-label">Usuario:</label>
                            <input type="text" class="form-control" name="usuario" required="true"
                            value="<?php echo htmlspecialchars($valor['usuario']); ?>">
                        </div>
                        <!-- Contraseña (opcional en edición) -->
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (dejar vacío para mantener la actual):</label>
                            <input type="password" class="form-control" name="contrasena" placeholder="Nueva contraseña">
                        </div>
                        <!-- Confirmar contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Confirmar Nueva Contraseña:</label>
                            <input type="password" class="form-control" name="confirmar_contrasena" placeholder="Confirmar nueva contraseña">
                        </div>
                        <!-- Rol -->
                        <div class="mb-3">
                            <label class="form-label">Rol:</label>
                            <select name="fk_rol" class="form-control" required>
                                <?php
                                $roles = ModeloUsuario::cargarRolesModelo();
                                foreach ($roles as $rol) {
                                    $selected = ($rol['pk_rol'] == $valor['fk_rol']) ? 'selected' : '';
                                    echo '<option value="' . $rol['pk_rol'] . '" ' . $selected . '>' . $rol['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Usuario</button>
                    <a href="index.php?opcion=mostrar_dato_usuario" class="btn btn-danger">Salir</a>
                </div>
                
                <script>
                // Validación de contraseñas en edición
                document.querySelector('form').addEventListener('submit', function(e) {
                    var contrasena = document.getElementsByName('contrasena')[0].value;
                    var confirmarContrasena = document.getElementsByName('confirmar_contrasena')[0].value;
                    
                    if (contrasena !== '' || confirmarContrasena !== '') {
                        if (contrasena !== confirmarContrasena) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Las contraseñas no coinciden'
                            });
                            return false;
                        }
                        
                        if (contrasena.length < 6) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'La contraseña debe tener al menos 6 caracteres'
                            });
                            return false;
                        }
                    }
                });
                </script>
                <?php
            }
        } 
    }

    // Actualizar usuario completo
    static public function actualizarUsuarioCompletoControlador()
    {
        if (isset($_POST["nombre"], $_POST["apellidos"], $_POST["edad"], $_POST["sexo"], 
                  $_POST["usuario"], $_POST["fk_rol"], $_POST["pk_usuario"], $_POST["pk_dato_usuario"])) {
            
            // Verificar si el usuario ya existe (excluyendo el actual)
            $usuarioExistente = ModeloUsuario::verificarUsuarioExistenteModelo($_POST['usuario'], $_POST['pk_usuario']);
            
            if ($usuarioExistente) {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El nombre de usuario ya existe'
                    });
                </script>
                <?php
                return;
            }
            
            $datosPersona = array(
                "nombre" => $_POST['nombre'],
                "apellidos" => $_POST['apellidos'],
                "edad" => $_POST['edad'],
                "sexo" => $_POST['sexo'],
                "pk_dato_usuario" => $_POST['pk_dato_usuario']
            );
            
            $datosUsuario = array(
                "usuario" => $_POST['usuario'],
                "fk_rol" => $_POST['fk_rol'],
                "pk_usuario" => $_POST['pk_usuario']
            );
            
            // Si se proporciona nueva contraseña, encriptarla
            if (!empty($_POST['contrasena'])) {
                $datosUsuario['contrasena'] = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            }

            $respuesta = ModeloUsuario::actualizarUsuarioCompletoModelo($datosPersona, $datosUsuario);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Usuario actualizado!',
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
                        title: 'Error',
                        text: '<?php echo $respuesta; ?>'
                    });
                </script>
                <?php
            }
        }
    }
}