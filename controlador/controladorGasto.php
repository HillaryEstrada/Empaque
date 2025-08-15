<?php

class ControladorGasto
{
    // ALTA GASTO COMPLETO (gasto + gasto_llegada/gasto_salida + venta opcional)
    static public function registroGastoCompletoControlador($nombre, $descripcion, $tipo) {
        $datosGasto = array(
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "tipo" => $tipo
        );
        
        // Datos específicos según el tipo
        if ($tipo == 'llegada') {
            if (!isset($_POST['fk_lote_llegada']) || !isset($_POST['monto_llegada']) || 
                empty($_POST['fk_lote_llegada']) || empty($_POST['monto_llegada'])) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Debe completar todos los campos para gasto de llegada"
                    });
                </script>';
                return;
            }
            
            $datosEspecificos = array(
                "fk_lote" => $_POST['fk_lote_llegada'],
                "monto" => $_POST['monto_llegada']
            );
            
            $respuesta = ModeloGasto::registroGastoLlegadaModelo($datosGasto, $datosEspecificos);
            
        } elseif ($tipo == 'salida') {
            if (!isset($_POST['fk_salida']) || !isset($_POST['monto_salida']) || 
                empty($_POST['fk_salida']) || empty($_POST['monto_salida'])) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Debe completar todos los campos para gasto de salida"
                    });
                </script>';
                return;
            }
            
            $datosEspecificos = array(
                "fk_salida" => $_POST['fk_salida'],
                "monto" => $_POST['monto_salida']
            );
            
            // Datos de venta (opcional)
            $datosVenta = null;
            if (!empty($_POST['ingreso_total']) || !empty($_POST['observaciones_venta'])) {
                $datosVenta = array(
                    "fk_salida" => $_POST['fk_salida'],
                    "ingreso_total" => !empty($_POST['ingreso_total']) ? $_POST['ingreso_total'] : 0,
                    "observaciones" => $_POST['observaciones_venta']
                );
            }
            
            $respuesta = ModeloGasto::registroGastoSalidaModelo($datosGasto, $datosEspecificos, $datosVenta);
        }

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Gasto creado exitosamente",
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

    // Cargar lotes activos para el select
    static public function cargarLotesControlador() {
        $respuesta = ModeloGasto::cargarLotesModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_lote'] . '">Lote ' . $valores['pk_lote'] . ' - ' . $valores['variedad'] . '</option>';
        }
    }

    // Cargar salidas activas para el select
    static public function cargarSalidasControlador() {
        $respuesta = ModeloGasto::cargarSalidasModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_salida'] . '">Salida ' . $valores['pk_salida'] . ' - ' . $valores['cliente'] . ' (' . $valores['tipo_salida'] . ')</option>';
        }
    }

    // Mostrar gastos completos
    static public function mostrarGastosCompletosControlador($estado)
    {
        $respuesta = ModeloGasto::mostrarGastosCompletosModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td><?php echo $valores['nombre']; ?></td>
                <td><?php echo $valores['descripcion']; ?></td>
                <td><?php echo ucfirst($valores['tipo']); ?></td>
                <td>
                    <?php 
                    if ($valores['tipo'] == 'llegada') {
                        echo 'Lote ' . $valores['referencia'];
                    } else {
                        echo 'Salida ' . $valores['referencia'] . ' - ' . $valores['cliente'];
                    }
                    ?>
                </td>
                <td>$<?php echo number_format($valores['monto'], 2); ?></td>
                <td>
                    <?php 
                    if ($valores['tipo'] == 'salida' && !empty($valores['ingreso_total'])) {
                        echo '$' . number_format($valores['ingreso_total'], 2);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_gasto', 
                                    pk_gasto: '<?php echo htmlspecialchars($valores['pk_gasto']); ?>', 
                                    <?php if ($valores['tipo'] == 'llegada'): ?>
                                    pk_gasto_llegada: '<?php echo htmlspecialchars($valores['pk_gasto_llegada']); ?>'
                                    <?php else: ?>
                                    pk_gasto_salida: '<?php echo htmlspecialchars($valores['pk_gasto_salida']); ?>'<?php if (!empty($valores['pk_venta'])): ?>,
                                    pk_venta: '<?php echo htmlspecialchars($valores['pk_venta']); ?>'<?php endif; ?>
                                    <?php endif; ?>
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'gasto:<?php echo htmlspecialchars($valores['pk_gasto']); ?>:pk_gasto<?php if ($valores['tipo'] == 'llegada'): ?>,gasto_llegada:<?php echo htmlspecialchars($valores['pk_gasto_llegada']); ?>:pk_gasto_llegada<?php else: ?>,gasto_salida:<?php echo htmlspecialchars($valores['pk_gasto_salida']); ?>:pk_gasto_salida<?php if (!empty($valores['pk_venta'])): ?>,venta:<?php echo htmlspecialchars($valores['pk_venta']); ?>:pk_venta<?php endif; ?><?php endif; ?>',
                                redirect_tabla: 'gasto',
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
                                tablas: 'gasto:<?php echo htmlspecialchars($valores['pk_gasto']); ?>:pk_gasto<?php if ($valores['tipo'] == 'llegada'): ?>,gasto_llegada:<?php echo htmlspecialchars($valores['pk_gasto_llegada']); ?>:pk_gasto_llegada<?php else: ?>,gasto_salida:<?php echo htmlspecialchars($valores['pk_gasto_salida']); ?>:pk_gasto_salida<?php if (!empty($valores['pk_venta'])): ?>,venta:<?php echo htmlspecialchars($valores['pk_venta']); ?>:pk_venta<?php endif; ?><?php endif; ?>',
                                redirect_tabla: 'gasto',
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

    // Obtener información para editar gasto completo
    static public function editarGastoCompletoControlador()
    {
        if (isset($_POST['pk_gasto'])) {
            $pk_gasto = $_POST['pk_gasto'];
            $pk_gasto_llegada = isset($_POST['pk_gasto_llegada']) ? $_POST['pk_gasto_llegada'] : null;
            $pk_gasto_salida = isset($_POST['pk_gasto_salida']) ? $_POST['pk_gasto_salida'] : null;
            $pk_venta = isset($_POST['pk_venta']) ? $_POST['pk_venta'] : null;
        
            $respuesta = ModeloGasto::editarGastoCompletoModelo($pk_gasto, $pk_gasto_llegada, $pk_gasto_salida, $pk_venta);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos del Gasto</h5>
                        <!-- Nombre del gasto -->
                        <div class="mb-3">
                            <label class="form-label">Nombre del Gasto:</label>
                            <input type="text" class="form-control" name="nombre" required="true"
                            value="<?php echo htmlspecialchars($valor['nombre']); ?>">
                        </div>
                        <!-- Descripción del gasto -->
                        <div class="mb-3">
                            <label class="form-label">Descripción:</label>
                            <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($valor['descripcion']); ?></textarea>
                        </div>
                        <!-- Tipo del gasto -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Gasto:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_llegada_edit" value="llegada" 
                                        <?php echo ($valor['tipo'] == 'llegada') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="tipo_llegada_edit">Llegada</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_salida_edit" value="salida" 
                                        <?php echo ($valor['tipo'] == 'salida') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="tipo_salida_edit">Salida</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos Específicos</h5>
                        
                        <!-- Campos para gasto de llegada -->
                        <div id="campos-llegada-edit" style="display: <?php echo ($valor['tipo'] == 'llegada') ? 'block' : 'none'; ?>;">
                            <div class="mb-3">
                                <label class="form-label">Lote:</label>
                                <select name="fk_lote" class="form-control" <?php echo ($valor['tipo'] == 'llegada') ? 'required' : ''; ?>>
                                    <?php
                                    $lotes = ModeloGasto::cargarLotesModelo();
                                    foreach ($lotes as $lote) {
                                        $selected = (isset($valor['fk_lote']) && $lote['pk_lote'] == $valor['fk_lote']) ? 'selected' : '';
                                        echo '<option value="' . $lote['pk_lote'] . '" ' . $selected . '>Lote ' . $lote['pk_lote'] . ' - ' . $lote['variedad'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Monto:</label>
                                <input type="number" class="form-control" name="monto_llegada" step="0.01" min="0"
                                value="<?php echo isset($valor['monto']) ? htmlspecialchars($valor['monto']) : ''; ?>" 
                                <?php echo ($valor['tipo'] == 'llegada') ? 'required' : ''; ?>>
                            </div>
                        </div>
                        
                        <!-- Campos para gasto de salida -->
                        <div id="campos-salida-edit" style="display: <?php echo ($valor['tipo'] == 'salida') ? 'block' : 'none'; ?>;">
                            <div class="mb-3">
                                <label class="form-label">Salida:</label>
                                <select name="fk_salida" class="form-control" <?php echo ($valor['tipo'] == 'salida') ? 'required' : ''; ?>>
                                    <?php
                                    $salidas = ModeloGasto::cargarSalidasModelo();
                                    foreach ($salidas as $salida) {
                                        $selected = (isset($valor['fk_salida']) && $salida['pk_salida'] == $valor['fk_salida']) ? 'selected' : '';
                                        echo '<option value="' . $salida['pk_salida'] . '" ' . $selected . '>Salida ' . $salida['pk_salida'] . ' - ' . $salida['cliente'] . ' (' . $salida['tipo_salida'] . ')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Monto:</label>
                                <input type="number" class="form-control" name="monto_salida" step="0.01" min="0"
                                value="<?php echo isset($valor['monto']) ? htmlspecialchars($valor['monto']) : ''; ?>" 
                                <?php echo ($valor['tipo'] == 'salida') ? 'required' : ''; ?>>
                            </div>
                        </div>
                        
                        <!-- Campos para venta (cuando el tipo es salida) -->
                        <div id="campos-venta-edit" style="display: <?php echo ($valor['tipo'] == 'salida') ? 'block' : 'none'; ?>;">
                            <h6 class="text-secondary mt-3">Datos de Venta (Opcional)</h6>
                            <div class="mb-3">
                                <label class="form-label">Ingreso Total:</label>
                                <input type="number" class="form-control" name="ingreso_total" step="0.01" min="0"
                                value="<?php echo isset($valor['ingreso_total']) ? htmlspecialchars($valor['ingreso_total']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Observaciones de Venta:</label>
                                <textarea class="form-control" name="observaciones_venta" rows="2"><?php echo isset($valor['observaciones_venta']) ? htmlspecialchars($valor['observaciones_venta']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Gasto</button>
                    <a href="index.php?opcion=mostrar_gasto" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        } 
    }

    // Editar gasto básico (solo datos de la tabla gasto)
    static public function editarGastoBasicoControlador()
    {
        if (isset($_POST['pk_gasto'])) {
            $pk_gasto = $_POST['pk_gasto'];
            $respuesta = ModeloGasto::editarGastoBasicoModelo($pk_gasto);
            
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <!-- Nombre del gasto -->
                <div class="mb-3">
                    <label class="form-label">Nombre del Gasto:</label>
                    <input type="text" class="form-control" name="nombre" required="true"
                    value="<?php echo htmlspecialchars($valor['nombre']); ?>">
                </div>
                <!-- Descripción del gasto -->
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($valor['descripcion']); ?></textarea>
                </div>
                <!-- Tipo del gasto -->
                <div class="mb-3">
                    <label class="form-label">Tipo de Gasto:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo_llegada_basic" value="llegada" 
                                <?php echo ($valor['tipo'] == 'llegada') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="tipo_llegada_basic">Llegada</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo_salida_basic" value="salida" 
                                <?php echo ($valor['tipo'] == 'salida') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="tipo_salida_basic">Salida</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Gasto</button>
                    <a href="index.php?opcion=mostrar_gasto" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        }
    }

    // Actualizar gasto de llegada
    static public function actualizarGastoLlegadaControlador()
    {
        if (isset($_POST["nombre"], $_POST["tipo"], $_POST["fk_lote"], $_POST["monto_llegada"], 
                  $_POST["pk_gasto"], $_POST["pk_gasto_llegada"])) {
            
            $datosGasto = array(
                "nombre" => $_POST['nombre'],
                "descripcion" => $_POST['descripcion'],
                "tipo" => $_POST['tipo'],
                "pk_gasto" => $_POST['pk_gasto']
            );
            
            $datosGastoLlegada = array(
                "fk_lote" => $_POST['fk_lote'],
                "monto" => $_POST['monto_llegada'],
                "pk_gasto_llegada" => $_POST['pk_gasto_llegada']
            );

            $respuesta = ModeloGasto::actualizarGastoLlegadaModelo($datosGasto, $datosGastoLlegada);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Gasto actualizado!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_gasto
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_gasto';

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

    // Actualizar gasto de salida
    static public function actualizarGastoSalidaControlador()
    {
        if (isset($_POST["nombre"], $_POST["tipo"], $_POST["fk_salida"], $_POST["monto_salida"], 
                  $_POST["pk_gasto"], $_POST["pk_gasto_salida"])) {
            
            $datosGasto = array(
                "nombre" => $_POST['nombre'],
                "descripcion" => $_POST['descripcion'],
                "tipo" => $_POST['tipo'],
                "pk_gasto" => $_POST['pk_gasto']
            );
            
            $datosGastoSalida = array(
                "fk_salida" => $_POST['fk_salida'],
                "monto" => $_POST['monto_salida'],
                "pk_gasto_salida" => $_POST['pk_gasto_salida']
            );
            
            // Datos de venta (opcional)
            $datosVenta = null;
            if (isset($_POST['pk_venta']) || !empty($_POST['ingreso_total']) || !empty($_POST['observaciones_venta'])) {
                $datosVenta = array(
                    "fk_salida" => $_POST['fk_salida'],
                    "ingreso_total" => !empty($_POST['ingreso_total']) ? $_POST['ingreso_total'] : 0,
                    "observaciones" => $_POST['observaciones_venta']
                );
                
                if (isset($_POST['pk_venta'])) {
                    $datosVenta['pk_venta'] = $_POST['pk_venta'];
                }
            }

            $respuesta = ModeloGasto::actualizarGastoSalidaModelo($datosGasto, $datosGastoSalida, $datosVenta);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Gasto actualizado!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_gasto
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_gasto';

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

    // Actualizar gasto básico
    static public function actualizarGastoBasicoControlador()
    {
        if (isset($_POST["nombre"], $_POST["tipo"], $_POST["pk_gasto"])) {
            
            $datosGasto = array(
                "nombre" => $_POST['nombre'],
                "descripcion" => $_POST['descripcion'],
                "tipo" => $_POST['tipo'],
                "pk_gasto" => $_POST['pk_gasto']
            );

            $respuesta = ModeloGasto::actualizarGastoBasicoModelo($datosGasto);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Gasto actualizado!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_gasto
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_gasto';

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