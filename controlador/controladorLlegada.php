<?php

class ControladorLlegada
{
    // ALTA LLEGADA COMPLETA (llegada_mango + detalle_llegada + pesaje + compra_mango)
    static public function registroLlegadaCompletaControlador($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra) {
        $respuesta = ModeloLlegada::registroLlegadaCompletaModelo($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra);

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Llegada registrada exitosamente",
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

    // Cargar ranchos activos para el select
    static public function cargarRanchosControlador() {
        $respuesta = ModeloLlegada::cargarRanchosModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_rancho'] . '">' . $valores['nombre'] . '</option>';
        }
    }

    // Cargar usuarios activos para el select
    static public function cargarUsuariosControlador() {
        $respuesta = ModeloLlegada::cargarUsuariosModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_usuario'] . '">' . $valores['usuario'] . ' - ' . $valores['nombre'] . ' ' . $valores['apellidos'] . '</option>';
        }
    }

    // Mostrar llegadas completas
    static public function mostrarLlegadasCompletasControlador($estado)
    {
        $respuesta = ModeloLlegada::mostrarLlegadasCompletasModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($valores['fecha'])); ?></td>
                <td><?php echo $valores['rancho_nombre']; ?></td>
                <td><?php echo $valores['usuario_nombre'] . ' ' . $valores['usuario_apellidos']; ?></td>
                <td><?php echo $valores['tipo_llegada']; ?></td>
                <td><?php echo $valores['medio_transporte']; ?></td>
                <td><?php echo $valores['responsable']; ?></td>
                <td><?php echo number_format($valores['peso_neto'], 2); ?></td>
                <td>$<?php echo number_format($valores['total_pagado'], 2); ?></td>
                <td><?php echo $valores['metodo_pago']; ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_llegada_mango', 
                                    pk_llegada: '<?php echo htmlspecialchars($valores['pk_llegada']); ?>'
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'llegada_mango:<?php echo htmlspecialchars($valores['pk_llegada']); ?>:pk_llegada,detalle_llegada:<?php echo htmlspecialchars($valores['pk_detalle_llegada']); ?>:pk_detalle_llegada,pesaje:<?php echo htmlspecialchars($valores['pk_pesaje']); ?>:pk_pesaje,compra_mango:<?php echo htmlspecialchars($valores['pk_compra']); ?>:pk_compra',
                                redirect_tabla: 'llegada_mango',
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
                                tablas: 'llegada_mango:<?php echo htmlspecialchars($valores['pk_llegada']); ?>:pk_llegada,detalle_llegada:<?php echo htmlspecialchars($valores['pk_detalle_llegada']); ?>:pk_detalle_llegada,pesaje:<?php echo htmlspecialchars($valores['pk_pesaje']); ?>:pk_pesaje,compra_mango:<?php echo htmlspecialchars($valores['pk_compra']); ?>:pk_compra',
                                redirect_tabla: 'llegada_mango',
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

    // Obtener información para editar llegada completa
    static public function editarLlegadaCompletaControlador()
    {
        if (isset($_POST['pk_llegada'])) {
            $pk_llegada = $_POST['pk_llegada'];
        
            $respuesta = ModeloLlegada::editarLlegadaCompletaModelo($pk_llegada);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de Llegada</h5>
                        <!-- Rancho -->
                        <div class="mb-3">
                            <label class="form-label">Rancho:</label>
                            <select name="fk_rancho" class="form-control" required>
                                <?php
                                $ranchos = ModeloLlegada::cargarRanchosModelo();
                                foreach ($ranchos as $rancho) {
                                    $selected = ($rancho['pk_rancho'] == $valor['fk_rancho']) ? 'selected' : '';
                                    echo '<option value="' . $rancho['pk_rancho'] . '" ' . $selected . '>' . $rancho['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Usuario -->
                        <div class="mb-3">
                            <label class="form-label">Usuario:</label>
                            <select name="fk_usuario" class="form-control" required>
                                <?php
                                $usuarios = ModeloLlegada::cargarUsuariosModelo();
                                foreach ($usuarios as $usuario) {
                                    $selected = ($usuario['pk_usuario'] == $valor['fk_usuario']) ? 'selected' : '';
                                    echo '<option value="' . $usuario['pk_usuario'] . '" ' . $selected . '>' . $usuario['usuario'] . ' - ' . $usuario['nombre'] . ' ' . $usuario['apellidos'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Tipo de llegada -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Llegada:</label>
                            <select name="tipo_llegada" class="form-control" required>
                                <option value="Compra Directa" <?php echo ($valor['tipo_llegada'] == 'Compra Directa') ? 'selected' : ''; ?>>Compra Directa</option>
                                <option value="Consignación" <?php echo ($valor['tipo_llegada'] == 'Consignación') ? 'selected' : ''; ?>>Consignación</option>
                                <option value="Maquila" <?php echo ($valor['tipo_llegada'] == 'Maquila') ? 'selected' : ''; ?>>Maquila</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Detalles de Transporte</h5>
                        <!-- Medio de transporte -->
                        <div class="mb-3">
                            <label class="form-label">Medio de Transporte:</label>
                            <input type="text" class="form-control" name="medio_transporte" required="true"
                            value="<?php echo htmlspecialchars($valor['medio_transporte']); ?>">
                        </div>
                        <!-- Tipo de envase -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Envase:</label>
                            <input type="text" class="form-control" name="tipo_envase" required="true"
                            value="<?php echo htmlspecialchars($valor['tipo_envase']); ?>">
                        </div>
                        <!-- Responsable -->
                        <div class="mb-3">
                            <label class="form-label">Responsable:</label>
                            <input type="text" class="form-control" name="responsable" required="true"
                            value="<?php echo htmlspecialchars($valor['responsable']); ?>">
                        </div>
                        <!-- Observaciones -->
                        <div class="mb-3">
                            <label class="form-label">Observaciones:</label>
                            <textarea name="observaciones" class="form-control" rows="3"><?php echo htmlspecialchars($valor['observaciones']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Datos de Pesaje y Compra -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de Pesaje</h5>
                        <!-- Peso bruto -->
                        <div class="mb-3">
                            <label class="form-label">Peso Bruto (kg):</label>
                            <input type="number" class="form-control" name="peso_bruto" step="0.01" min="0" required="true"
                            value="<?php echo $valor['peso_bruto']; ?>" onchange="calcularPesoNeto()">
                        </div>
                        <!-- Peso envase -->
                        <div class="mb-3">
                            <label class="form-label">Peso Envase (kg):</label>
                            <input type="number" class="form-control" name="peso_envase" step="0.01" min="0" required="true"
                            value="<?php echo $valor['peso_envase']; ?>" onchange="calcularPesoNeto()">
                        </div>
                        <!-- Peso neto -->
                        <div class="mb-3">
                            <label class="form-label">Peso Neto (kg):</label>
                            <input type="number" class="form-control" name="peso_neto" step="0.01" min="0" readonly
                            value="<?php echo $valor['peso_neto']; ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="text-primary">Datos de Compra</h5>
                        <!-- Precio por kilo -->
                        <div class="mb-3">
                            <label class="form-label">Precio por Kilo ($):</label>
                            <input type="number" class="form-control" name="precio_kilo" step="0.01" min="0" required="true"
                            value="<?php echo $valor['precio_kilo']; ?>" onchange="calcularTotal()">
                        </div>
                        <!-- Total pagado -->
                        <div class="mb-3">
                            <label class="form-label">Total Pagado ($):</label>
                            <input type="number" class="form-control" name="total_pagado" step="0.01" min="0" readonly
                            value="<?php echo $valor['total_pagado']; ?>">
                        </div>
                        <!-- Método de pago -->
                        <div class="mb-3">
                            <label class="form-label">Método de Pago:</label>
                            <select name="metodo_pago" class="form-control" required>
                                <option value="Efectivo" <?php echo ($valor['metodo_pago'] == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                                <option value="Transferencia" <?php echo ($valor['metodo_pago'] == 'Transferencia') ? 'selected' : ''; ?>>Transferencia</option>
                                <option value="Cheque" <?php echo ($valor['metodo_pago'] == 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
                                <option value="Crédito" <?php echo ($valor['metodo_pago'] == 'Crédito') ? 'selected' : ''; ?>>Crédito</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Llegada</button>
                    <a href="index.php?opcion=mostrar_llegada_mango" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        } 
    }

    // Actualizar llegada completa
    static public function actualizarLlegadaCompletaControlador()
    {
        if (isset($_POST["fk_rancho"], $_POST["fk_usuario"], $_POST["tipo_llegada"], 
                  $_POST["medio_transporte"], $_POST["tipo_envase"], $_POST["responsable"],
                  $_POST["peso_bruto"], $_POST["peso_envase"], $_POST["peso_neto"],
                  $_POST["precio_kilo"], $_POST["total_pagado"], $_POST["metodo_pago"],
                  $_POST["pk_llegada"])) {
            
            $datosLlegada = array(
                "fk_rancho" => $_POST['fk_rancho'],
                "fk_usuario" => $_POST['fk_usuario'],
                "tipo_llegada" => $_POST['tipo_llegada'],
                "pk_llegada" => $_POST['pk_llegada']
            );

            $datosDetalle = array(
                "medio_transporte" => $_POST['medio_transporte'],
                "tipo_envase" => $_POST['tipo_envase'],
                "responsable" => $_POST['responsable'],
                "observaciones" => isset($_POST['observaciones']) ? $_POST['observaciones'] : ''
            );

            $datosPesaje = array(
                "peso_bruto" => $_POST['peso_bruto'],
                "peso_envase" => $_POST['peso_envase'],
                "peso_neto" => $_POST['peso_neto']
            );

            $datosCompra = array(
                "precio_kilo" => $_POST['precio_kilo'],
                "total_pagado" => $_POST['total_pagado'],
                "metodo_pago" => $_POST['metodo_pago']
            );

            $respuesta = ModeloLlegada::actualizarLlegadaCompletaModelo($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Llegada actualizada!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_llegada_mango
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_llegada_mango';

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