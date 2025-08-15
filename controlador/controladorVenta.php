<?php

class ControladorVenta
{
    // ALTA VENTA COMPLETA (salida_mango + venta)
    static public function registroVentaCompletaControlador($fk_lote, $tipo_salida, $cliente, $destino, $transporte, $observaciones_salida, $ingreso_total, $observaciones_venta) {
        
        $datosSalida = array(
            "fk_lote" => $fk_lote,
            "tipo_salida" => $tipo_salida,
            "cliente" => $cliente,
            "destino" => $destino,
            "transporte" => $transporte,
            "observaciones" => $observaciones_salida
        );
        
        $datosVenta = array(
            "ingreso_total" => $ingreso_total,
            "observaciones" => $observaciones_venta
        );

        $respuesta = ModeloVenta::registroVentaCompletaModelo($datosSalida, $datosVenta);

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Venta creada exitosamente",
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
        $respuesta = ModeloVenta::cargarLotesModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_lote'] . '">Lote: ' . $valores['numero_lote'] . ' - ' . $valores['variedad'] . '</option>';
        }
    }

    // Mostrar ventas completas (salida_mango + venta + lote)
    static public function mostrarVentasCompletasControlador($estado)
    {
        $respuesta = ModeloVenta::mostrarVentasCompletasModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td><?php echo $valores['cliente']; ?></td>
                <td><?php echo 'Lote: ' . $valores['numero_lote'] . ' - ' . $valores['variedad']; ?></td>
                <td><?php echo $valores['destino']; ?></td>
                <td><?php echo $valores['tipo_salida']; ?></td>
                <td><?php echo $valores['transporte']; ?></td>
                <td>$<?php echo number_format($valores['ingreso_total'], 2); ?></td>
                <td><?php echo date('d/m/Y', strtotime($valores['fecha_venta'])); ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_venta', 
                                    pk_salida: '<?php echo htmlspecialchars($valores['pk_salida']); ?>', 
                                    pk_venta: '<?php echo htmlspecialchars($valores['pk_venta']); ?>'
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'salida_mango:<?php echo htmlspecialchars($valores['pk_salida']); ?>:pk_salida,venta:<?php echo htmlspecialchars($valores['pk_venta']); ?>:pk_venta',
                                redirect_tabla: 'venta',
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
                                tablas: 'salida_mango:<?php echo htmlspecialchars($valores['pk_salida']); ?>:pk_salida,venta:<?php echo htmlspecialchars($valores['pk_venta']); ?>:pk_venta',
                                redirect_tabla: 'venta',
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

    // Obtener información para editar venta completa
    static public function editarVentaCompletaControlador()
    {
        if (isset($_POST['pk_salida']) && isset($_POST['pk_venta'])) {
            $pk_salida = $_POST['pk_salida'];
            $pk_venta = $_POST['pk_venta'];
        
            $respuesta = ModeloVenta::editarVentaCompletaModelo($pk_salida, $pk_venta);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de la Salida</h5>
                        <!-- Lote -->
                        <div class="mb-3">
                            <label class="form-label">Lote:</label>
                            <select name="fk_lote" class="form-control" required>
                                <?php
                                $lotes = ModeloVenta::cargarLotesModelo();
                                foreach ($lotes as $lote) {
                                    $selected = ($lote['pk_lote'] == $valor['fk_lote']) ? 'selected' : '';
                                    echo '<option value="' . $lote['pk_lote'] . '" ' . $selected . '>Lote: ' . $lote['numero_lote'] . ' - ' . $lote['variedad'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Tipo de salida -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Salida:</label>
                            <input type="text" class="form-control" name="tipo_salida"
                            value="<?php echo htmlspecialchars($valor['tipo_salida']); ?>">
                        </div>
                        <!-- Cliente -->
                        <div class="mb-3">
                            <label class="form-label">Cliente:</label>
                            <input type="text" class="form-control" name="cliente" required="true"
                            value="<?php echo htmlspecialchars($valor['cliente']); ?>">
                        </div>
                        <!-- Destino -->
                        <div class="mb-3">
                            <label class="form-label">Destino:</label>
                            <textarea class="form-control" name="destino" rows="2"><?php echo htmlspecialchars($valor['destino']); ?></textarea>
                        </div>
                        <!-- Transporte -->
                        <div class="mb-3">
                            <label class="form-label">Transporte:</label>
                            <input type="text" class="form-control" name="transporte"
                            value="<?php echo htmlspecialchars($valor['transporte']); ?>">
                        </div>
                        <!-- Observaciones de salida -->
                        <div class="mb-3">
                            <label class="form-label">Observaciones de Salida:</label>
                            <textarea class="form-control" name="observaciones_salida" rows="2"><?php echo htmlspecialchars($valor['observaciones_salida']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de la Venta</h5>
                        <!-- Ingreso total -->
                        <div class="mb-3">
                            <label class="form-label">Ingreso Total:</label>
                            <input type="number" class="form-control" name="ingreso_total" step="0.01" min="0" required="true"
                            value="<?php echo htmlspecialchars($valor['ingreso_total']); ?>">
                        </div>
                        <!-- Observaciones de venta -->
                        <div class="mb-3">
                            <label class="form-label">Observaciones de Venta:</label>
                            <textarea class="form-control" name="observaciones_venta" rows="4"><?php echo htmlspecialchars($valor['observaciones_venta']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Venta</button>
                    <a href="index.php?opcion=mostrar_venta" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        } 
    }

    // Obtener información para editar venta básica
    static public function editarVentaBasicaControlador()
    {
        if (isset($_POST['pk_venta'])) {
            $pk_venta = $_POST['pk_venta'];
            $respuesta = ModeloVenta::editarVentaBasicaModelo($pk_venta);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="mb-3">
                    <label class="form-label">Ingreso Total:</label>
                    <input type="number" class="form-control" name="ingreso_total" step="0.01" min="0" required="true"
                    value="<?php echo htmlspecialchars($valor['ingreso_total']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Observaciones:</label>
                    <textarea class="form-control" name="observaciones" rows="4"><?php echo htmlspecialchars($valor['observaciones']); ?></textarea>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Venta</button>
                    <a href="index.php?opcion=mostrar_venta" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        }
    }

    // Actualizar venta completa
    static public function actualizarVentaCompletaControlador()
    {
        if (isset($_POST["fk_lote"], $_POST["cliente"], $_POST["ingreso_total"], $_POST["pk_salida"], $_POST["pk_venta"])) {
            
            $datosSalida = array(
                "fk_lote" => $_POST['fk_lote'],
                "tipo_salida" => $_POST['tipo_salida'],
                "cliente" => $_POST['cliente'],
                "destino" => $_POST['destino'],
                "transporte" => $_POST['transporte'],
                "observaciones" => $_POST['observaciones_salida'],
                "pk_salida" => $_POST['pk_salida']
            );
            
            $datosVenta = array(
                "ingreso_total" => $_POST['ingreso_total'],
                "observaciones" => $_POST['observaciones_venta'],
                "pk_venta" => $_POST['pk_venta']
            );

            $respuesta = ModeloVenta::actualizarVentaCompletaModelo($datosSalida, $datosVenta);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Venta actualizada!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_venta
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_venta';

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

    // Actualizar venta básica
    static public function actualizarVentaBasicaControlador()
    {
        if (isset($_POST["ingreso_total"], $_POST["pk_venta"])) {
            
            $datosVenta = array(
                "ingreso_total" => $_POST['ingreso_total'],
                "observaciones" => $_POST['observaciones'],
                "pk_venta" => $_POST['pk_venta']
            );

            $respuesta = ModeloVenta::actualizarVentaBasicaModelo($datosVenta);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Venta actualizada!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_venta
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_venta';

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