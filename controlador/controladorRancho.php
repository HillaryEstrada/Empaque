<?php

class ControladorRancho
{
    // ALTA RANCHO COMPLETO (productor + rancho)
    static public function registroRanchoCompletoControlador($nombre_productor, $telefono, $nombre_rancho, $ubicacion) {
        
        $datosProductor = array(
            "nombre" => $nombre_productor,
            "telefono" => $telefono
        );
        
        $datosRancho = array(
            "nombre" => $nombre_rancho,
            "ubicacion" => $ubicacion
        );

        $respuesta = ModeloRancho::registroRanchoCompletoModelo($datosProductor, $datosRancho);

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Rancho creado exitosamente",
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

    // Mostrar ranchos completos (productor + rancho)
    static public function mostrarRanchosCompletosControlador($estado)
    {
        $respuesta = ModeloRancho::mostrarRanchosCompletosModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td><?php echo $valores['nombre_productor']; ?></td>
                <td><?php echo $valores['telefono'] ? $valores['telefono'] : 'N/A'; ?></td>
                <td><?php echo $valores['nombre_rancho']; ?></td>
                <td><?php echo $valores['ubicacion'] ? $valores['ubicacion'] : 'N/A'; ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_rancho', 
                                    pk_rancho: '<?php echo htmlspecialchars($valores['pk_rancho']); ?>', 
                                    pk_productor: '<?php echo htmlspecialchars($valores['pk_productor']); ?>'
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'rancho:<?php echo htmlspecialchars($valores['pk_rancho']); ?>:pk_rancho,productor:<?php echo htmlspecialchars($valores['pk_productor']); ?>:pk_productor',
                                redirect_tabla: 'rancho',
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
                                tablas: 'rancho:<?php echo htmlspecialchars($valores['pk_rancho']); ?>:pk_rancho,productor:<?php echo htmlspecialchars($valores['pk_productor']); ?>:pk_productor',
                                redirect_tabla: 'rancho',
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

    // Obtener información para editar rancho completo
    static public function editarRanchoCompletoControlador()
    {
        if (isset($_POST['pk_rancho']) && isset($_POST['pk_productor'])) {
            $pk_rancho = $_POST['pk_rancho'];
            $pk_productor = $_POST['pk_productor'];
        
            $respuesta = ModeloRancho::editarRanchoCompletoModelo($pk_rancho, $pk_productor);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos del Productor</h5>
                        <!-- Nombre del productor -->
                        <div class="mb-3">
                            <label class="form-label">Nombre del Productor:</label>
                            <input type="text" class="form-control" name="nombre_productor" required="true"
                            value="<?php echo htmlspecialchars($valor['nombre_productor']); ?>">
                        </div>
                        <!-- Teléfono del productor -->
                        <div class="mb-3">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" name="telefono"
                            value="<?php echo htmlspecialchars($valor['telefono']); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos del Rancho</h5>
                        <!-- Nombre del rancho -->
                        <div class="mb-3">
                            <label class="form-label">Nombre del Rancho:</label>
                            <input type="text" class="form-control" name="nombre_rancho" required="true"
                            value="<?php echo htmlspecialchars($valor['nombre_rancho']); ?>">
                        </div>
                        <!-- Ubicación del rancho -->
                        <div class="mb-3">
                            <label class="form-label">Ubicación:</label>
                            <textarea class="form-control" name="ubicacion" rows="3"><?php echo htmlspecialchars($valor['ubicacion']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Rancho</button>
                    <a href="index.php?opcion=mostrar_rancho" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        } 
    }

    // Actualizar rancho completo
    static public function actualizarRanchoCompletoControlador()
    {
        if (isset($_POST["nombre_productor"], $_POST["nombre_rancho"], 
                  $_POST["pk_rancho"], $_POST["pk_productor"])) {
            
            $datosProductor = array(
                "nombre" => $_POST['nombre_productor'],
                "telefono" => isset($_POST['telefono']) ? $_POST['telefono'] : '',
                "pk_productor" => $_POST['pk_productor']
            );
            
            $datosRancho = array(
                "nombre" => $_POST['nombre_rancho'],
                "ubicacion" => isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '',
                "pk_rancho" => $_POST['pk_rancho']
            );

            $respuesta = ModeloRancho::actualizarRanchoCompletoModelo($datosProductor, $datosRancho);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Rancho actualizado!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_rancho
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_rancho';

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