<?php

class ControladorGasto
{
    // ALTA GASTO COMPLETO (gasto + gasto_llegada o gasto_salida)
    static public function registroGastoCompletoControlador($nombre, $descripcion, $tipo, $monto, $fk_referencia) {
        
        $datosGasto = array(
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "tipo" => $tipo
        );
        
        $datosDetalle = array(
            "monto" => $monto
        );
        
        // Según el tipo, agregar la referencia correspondiente
        if ($tipo === 'llegada') {
            $datosDetalle['fk_lote'] = $fk_referencia;
        } elseif ($tipo === 'salida') {
            $datosDetalle['fk_salida'] = $fk_referencia;
        }

        $respuesta = ModeloGasto::registroGastoCompletoModelo($datosGasto, $datosDetalle, $tipo);

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
            echo '<option value="' . $valores['pk_lote'] . '">Lote: ' . $valores['numero_lote'] . ' - ' . $valores['variedad'] . '</option>';
        }
    }

    // Cargar salidas activas para el select
    static public function cargarSalidasControlador() {
        $respuesta = ModeloGasto::cargarSalidasModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            $infoLote = !empty($valores['numero_lote']) ? ' (Lote: ' . $valores['numero_lote'] . ' - ' . $valores['variedad'] . ')' : '';
            $tipoSalida = !empty($valores['tipo_salida']) ? ' [' . $valores['tipo_salida'] . ']' : '';
            echo '<option value="' . $valores['pk_salida'] . '">' . $valores['cliente'] . ' → ' . $valores['destino'] . $tipoSalida . $infoLote . '</option>';
        }
    }

    // Mostrar gastos completos (gasto + detalle según tipo)
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
                    if ($valores['tipo'] === 'llegada' && !empty($valores['referencia_info'])) {
                        echo $valores['referencia_info'];
                    } elseif ($valores['tipo'] === 'salida' && !empty($valores['referencia_info'])) {
                        echo $valores['referencia_info'];
                    } else {
                        echo 'Sin referencia';
                    }
                    ?>
                </td>
                <td>$<?php echo number_format($valores['monto'], 2); ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_gasto', 
                                    pk_gasto: '<?php echo htmlspecialchars($valores['pk_gasto']); ?>',
                                    tipo: '<?php echo htmlspecialchars($valores['tipo']); ?>',
                                    <?php 
                                    if ($valores['tipo'] === 'llegada') {
                                        echo "pk_gasto_llegada: '" . htmlspecialchars($valores['pk_detalle']) . "'";
                                    } else {
                                        echo "pk_gasto_salida: '" . htmlspecialchars($valores['pk_detalle']) . "'";
                                    }
                                    ?>
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'gasto:<?php echo htmlspecialchars($valores['pk_gasto']); ?>:pk_gasto,<?php echo ($valores['tipo'] === 'llegada' ? 'gasto_llegada' : 'gasto_salida'); ?>:<?php echo htmlspecialchars($valores['pk_detalle']); ?>:<?php echo ($valores['tipo'] === 'llegada' ? 'pk_gasto_llegada' : 'pk_gasto_salida'); ?>',
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
                                tablas: 'gasto:<?php echo htmlspecialchars($valores['pk_gasto']); ?>:pk_gasto,<?php echo ($valores['tipo'] === 'llegada' ? 'gasto_llegada' : 'gasto_salida'); ?>:<?php echo htmlspecialchars($valores['pk_detalle']); ?>:<?php echo ($valores['tipo'] === 'llegada' ? 'pk_gasto_llegada' : 'pk_gasto_salida'); ?>',
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
        if (isset($_POST['pk_gasto']) && isset($_POST['tipo_original'])) {
            $pk_gasto = $_POST['pk_gasto'];
            $tipo = $_POST['tipo_original'];
            
            if ($tipo === 'llegada' && isset($_POST['pk_gasto_llegada'])) {
                $pk_detalle = $_POST['pk_gasto_llegada'];
            } elseif ($tipo === 'salida' && isset($_POST['pk_gasto_salida'])) {
                $pk_detalle = $_POST['pk_gasto_salida'];
            } else {
                return;
            }
        
            $respuesta = ModeloGasto::editarGastoCompletoModelo($pk_gasto, $pk_detalle, $tipo);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos del Gasto</h5>
                        <!-- Nombre del gasto -->
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
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
                        <h5 class="text-primary">Datos del Detalle</h5>
                        
                        <!-- Campo para Lote -->
                        <div class="mb-3" id="campo-lote" style="<?php echo ($valor['tipo'] === 'llegada') ? 'display: block;' : 'display: none;'; ?>">
                            <label class="form-label">Lote:</label>
                            <select name="fk_lote" class="form-control">
                                <option value="">Seleccionar lote...</option>
                                <?php
                                $lotes = ModeloGasto::cargarLotesModelo();
                                foreach ($lotes as $lote) {
                                    $selected = (isset($valor['fk_lote']) && $lote['pk_lote'] == $valor['fk_lote']) ? 'selected' : '';
                                    echo '<option value="' . $lote['pk_lote'] . '" ' . $selected . '>Lote: ' . $lote['numero_lote'] . ' - ' . $lote['variedad'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Campo para Salida -->
                        <div class="mb-3" id="campo-salida" style="<?php echo ($valor['tipo'] === 'salida') ? 'display: block;' : 'display: none;'; ?>">
                            <label class="form-label">Salida:</label>
                            <select name="fk_salida" class="form-control">
                                <option value="">Seleccionar salida...</option>
                                <?php
                                $salidas = ModeloGasto::cargarSalidasModelo();
                                foreach ($salidas as $salida) {
                                    $selected = (isset($valor['fk_salida']) && $salida['pk_salida'] == $valor['fk_salida']) ? 'selected' : '';
                                    $infoLote = !empty($salida['numero_lote']) ? ' (Lote: ' . $salida['numero_lote'] . ' - ' . $salida['variedad'] . ')' : '';
                                    $tipoSalida = !empty($salida['tipo_salida']) ? ' [' . $salida['tipo_salida'] . ']' : '';
                                    echo '<option value="' . $salida['pk_salida'] . '" ' . $selected . '>' . $salida['cliente'] . ' → ' . $salida['destino'] . $tipoSalida . $infoLote . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Monto -->
                        <div class="mb-3">
                            <label class="form-label">Monto:</label>
                            <input type="number" class="form-control" name="monto" step="0.01" min="0" required="true"
                            value="<?php echo htmlspecialchars($valor['monto']); ?>">
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

    // Obtener información para editar gasto básico
    static public function editarGastoBasicoControlador()
    {
        if (isset($_POST['pk_gasto'])) {
            $pk_gasto = $_POST['pk_gasto'];
            $respuesta = ModeloGasto::editarGastoBasicoModelo($pk_gasto);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required="true"
                    value="<?php echo htmlspecialchars($valor['nombre']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($valor['descripcion']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo:</label>
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

    // Actualizar gasto completo
    static public function actualizarGastoCompletoControlador()
    {
        if (isset($_POST["nombre"], $_POST["tipo"], $_POST["monto"], $_POST["pk_gasto"], $_POST["tipo_original"])) {
            
            $datosGasto = array(
                "nombre" => $_POST['nombre'],
                "descripcion" => $_POST['descripcion'],
                "tipo" => $_POST['tipo'],
                "pk_gasto" => $_POST['pk_gasto']
            );
            
            $datosDetalle = array(
                "monto" => $_POST['monto']
            );
            
            $tipo_original = $_POST['tipo_original'];
            $tipo_nuevo = $_POST['tipo'];
            
            // Agregar claves de detalle según el tipo original
            if ($tipo_original === 'llegada' && isset($_POST['pk_gasto_llegada'])) {
                $datosDetalle['pk_gasto_llegada'] = $_POST['pk_gasto_llegada'];
            } elseif ($tipo_original === 'salida' && isset($_POST['pk_gasto_salida'])) {
                $datosDetalle['pk_gasto_salida'] = $_POST['pk_gasto_salida'];
            }
            
            // Agregar referencia según el tipo nuevo
            if ($tipo_nuevo === 'llegada' && !empty($_POST['fk_lote'])) {
                $datosDetalle['fk_lote'] = $_POST['fk_lote'];
            } elseif ($tipo_nuevo === 'salida' && !empty($_POST['fk_salida'])) {
                $datosDetalle['fk_salida'] = $_POST['fk_salida'];
            }

            $respuesta = ModeloGasto::actualizarGastoCompletoModelo($datosGasto, $datosDetalle, $tipo_original, $tipo_nuevo);

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