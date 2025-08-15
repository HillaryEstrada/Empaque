<?php

class ControladorCalidad
{
    // ALTA CALIDAD COMPLETA (revision_calidad + lote + clasificacion)
    static public function registroCalidadCompletaControlador($fk_llegada, $madurez, $plagas, $danos, $contaminantes, 
                                                             $observaciones_revision, $numero_lote, $variedad, 
                                                             $primera_calidad, $segunda_calidad, $descarte, $uso, 
                                                             $observaciones_clasificacion) {
        
        // Verificar que el número de lote no exista para la misma llegada
        $verificarLote = ModeloCalidad::verificarLoteExistenteModelo($numero_lote, $fk_llegada);
        
        if ($verificarLote) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "El número de lote ya existe para esta llegada"
                });
            </script>';
            return;
        }
        
        $datosRevision = array(
            "fk_llegada" => $fk_llegada,
            "madurez" => $madurez,
            "plagas" => $plagas,
            "danos" => $danos,
            "contaminantes" => $contaminantes,
            "observaciones" => $observaciones_revision
        );
        
        $datosLote = array(
            "fk_llegada" => $fk_llegada,
            "numero_lote" => $numero_lote,
            "variedad" => $variedad
        );
        
        $datosClasificacion = array(
            "primera_calidad" => $primera_calidad ?: null,
            "segunda_calidad" => $segunda_calidad ?: null,
            "descarte" => $descarte ?: null,
            "uso" => $uso,
            "observaciones" => $observaciones_clasificacion
        );

        $respuesta = ModeloCalidad::registroCalidadCompletaModelo($datosRevision, $datosLote, $datosClasificacion);

        if ($respuesta == 'ok') {
            echo '<script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Calidad completa creada exitosamente",
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

    // Cargar llegadas activas para el select
    static public function cargarLlegadasControlador() {
        $respuesta = ModeloCalidad::cargarLlegadasModelo();
        
        foreach ($respuesta as $renglon => $valores) {
            echo '<option value="' . $valores['pk_llegada'] . '">Llegada #' . $valores['pk_llegada'] . ' - ' . $valores['fecha'] . '</option>';
        }
    }

    // Mostrar calidad completa (revision_calidad + lote + clasificacion)
    static public function mostrarCalidadCompletaControlador($estado)
    {
        $respuesta = ModeloCalidad::mostrarCalidadCompletaModelo($estado);
    
        foreach ($respuesta as $renglon => $valores) {
            ?>
            <tr>
                <td>Llegada #<?php echo $valores['fk_llegada']; ?></td>
                <td><?php echo $valores['madurez']; ?></td>
                <td><?php echo ($valores['plagas'] == 1) ? 'Sí' : 'No'; ?></td>
                <td><?php echo ($valores['danos'] == 1) ? 'Sí' : 'No'; ?></td>
                <td><?php echo ($valores['contaminantes'] == 1) ? 'Sí' : 'No'; ?></td>
                <td><?php echo $valores['numero_lote']; ?></td>
                <td><?php echo $valores['variedad'] ?: 'N/A'; ?></td>
                <td><?php echo $valores['primera_calidad'] ?: '0.00'; ?> kg</td>
                <td><?php echo $valores['segunda_calidad'] ?: '0.00'; ?> kg</td>
                <td><?php echo $valores['descarte'] ?: '0.00'; ?> kg</td>
                <td><?php echo $valores['uso']; ?></td>
                <td>
                    <?php if ($estado == 1): ?>
                        <!-- Botón de Editar -->
                        <button 
                                class="btn btn-warning btn-sm"
                                onclick="postToExternalSite('index.php', { 
                                    opcion: 'editar_calidad', 
                                    pk_revision: '<?php echo htmlspecialchars($valores['pk_revision']); ?>', 
                                    pk_lote: '<?php echo htmlspecialchars($valores['pk_lote']); ?>',
                                    pk_clasificacion: '<?php echo htmlspecialchars($valores['pk_clasificacion']); ?>'
                                });">
                                <i class="fa-solid fa-pencil"></i> Editar
                        </button>
                        
                        <!-- Botón de Desactivar usando archivos multitabla -->
                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="postToExternalSite('index.php', { 
                                opcion: 'desactivar_multitabla',
                                tablas: 'revision_calidad:<?php echo htmlspecialchars($valores['pk_revision']); ?>:pk_revision,lote:<?php echo htmlspecialchars($valores['pk_lote']); ?>:pk_lote,clasificacion:<?php echo htmlspecialchars($valores['pk_clasificacion']); ?>:pk_clasificacion',
                                redirect_tabla: 'calidad',
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
                                tablas: 'revision_calidad:<?php echo htmlspecialchars($valores['pk_revision']); ?>:pk_revision,lote:<?php echo htmlspecialchars($valores['pk_lote']); ?>:pk_lote,clasificacion:<?php echo htmlspecialchars($valores['pk_clasificacion']); ?>:pk_clasificacion',
                                redirect_tabla: 'calidad',
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

    // Obtener información para editar calidad completa
    static public function editarCalidadCompletaControlador()
    {
        if (isset($_POST['pk_revision']) && isset($_POST['pk_lote']) && isset($_POST['pk_clasificacion'])) {
            $pk_revision = $_POST['pk_revision'];
            $pk_lote = $_POST['pk_lote'];
            $pk_clasificacion = $_POST['pk_clasificacion'];
        
            $respuesta = ModeloCalidad::editarCalidadCompletaModelo($pk_revision, $pk_lote, $pk_clasificacion);
        
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="text-primary">Revisión de Calidad</h5>
                        <!-- Llegada -->
                        <div class="mb-3">
                            <label class="form-label">Llegada:</label>
                            <select name="fk_llegada" class="form-control" required>
                                <?php
                                $llegadas = ModeloCalidad::cargarLlegadasModelo();
                                foreach ($llegadas as $llegada) {
                                    $selected = ($llegada['pk_llegada'] == $valor['fk_llegada']) ? 'selected' : '';
                                    echo '<option value="' . $llegada['pk_llegada'] . '" ' . $selected . '>Llegada #' . $llegada['pk_llegada'] . ' - ' . $llegada['fecha'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Madurez -->
                        <div class="mb-3">
                            <label class="form-label">Madurez:</label>
                            <select name="madurez" class="form-control" required>
                                <option value="Verde" <?php echo ($valor['madurez'] == 'Verde') ? 'selected' : ''; ?>>Verde</option>
                                <option value="Maduro" <?php echo ($valor['madurez'] == 'Maduro') ? 'selected' : ''; ?>>Maduro</option>
                                <option value="Muy Maduro" <?php echo ($valor['madurez'] == 'Muy Maduro') ? 'selected' : ''; ?>>Muy Maduro</option>
                            </select>
                        </div>
                        <!-- Plagas -->
                        <div class="mb-3">
                            <label class="form-label">Plagas:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="plagas" id="plagas_si_edit" value="1" 
                                        <?php echo ($valor['plagas'] == 1) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="plagas_si_edit">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="plagas" id="plagas_no_edit" value="0" 
                                        <?php echo ($valor['plagas'] == 0) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="plagas_no_edit">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Daños -->
                        <div class="mb-3">
                            <label class="form-label">Daños:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="danos" id="danos_si_edit" value="1" 
                                        <?php echo ($valor['danos'] == 1) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="danos_si_edit">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="danos" id="danos_no_edit" value="0" 
                                        <?php echo ($valor['danos'] == 0) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="danos_no_edit">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Contaminantes -->
                        <div class="mb-3">
                            <label class="form-label">Contaminantes:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_si_edit" value="1" 
                                        <?php echo ($valor['contaminantes'] == 1) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="contaminantes_si_edit">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_no_edit" value="0" 
                                        <?php echo ($valor['contaminantes'] == 0) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="contaminantes_no_edit">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Observaciones Revisión -->
                        <div class="mb-3">
                            <label class="form-label">Observaciones Revisión:</label>
                            <textarea name="observaciones_revision" class="form-control" rows="3"><?php echo htmlspecialchars($valor['observaciones_revision'] ?: ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h5 class="text-primary">Datos del Lote</h5>
                        <!-- Número de Lote -->
                        <div class="mb-3">
                            <label class="form-label">Número de Lote:</label>
                            <input type="text" class="form-control" name="numero_lote" required="true"
                            value="<?php echo htmlspecialchars($valor['numero_lote']); ?>">
                        </div>
                        <!-- Variedad -->
                        <div class="mb-3">
                            <label class="form-label">Variedad:</label>
                            <input type="text" class="form-control" name="variedad"
                            value="<?php echo htmlspecialchars($valor['variedad'] ?: ''); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h5 class="text-primary">Clasificación</h5>
                        <!-- Primera Calidad -->
                        <div class="mb-3">
                            <label class="form-label">Primera Calidad (kg):</label>
                            <input type="number" class="form-control" name="primera_calidad" step="0.01" min="0"
                            value="<?php echo $valor['primera_calidad'] ?: ''; ?>">
                        </div>
                        <!-- Segunda Calidad -->
                        <div class="mb-3">
                            <label class="form-label">Segunda Calidad (kg):</label>
                            <input type="number" class="form-control" name="segunda_calidad" step="0.01" min="0"
                            value="<?php echo $valor['segunda_calidad'] ?: ''; ?>">
                        </div>
                        <!-- Descarte -->
                        <div class="mb-3">
                            <label class="form-label">Descarte (kg):</label>
                            <input type="number" class="form-control" name="descarte" step="0.01" min="0"
                            value="<?php echo $valor['descarte'] ?: ''; ?>">
                        </div>
                        <!-- Uso -->
                        <div class="mb-3">
                            <label class="form-label">Uso:</label>
                            <input type="text" class="form-control" name="uso" required="true"
                            value="<?php echo htmlspecialchars($valor['uso']); ?>">
                        </div>
                        <!-- Observaciones Clasificación -->
                        <div class="mb-3">
                            <label class="form-label">Observaciones Clasificación:</label>
                            <textarea name="observaciones_clasificacion" class="form-control" rows="3"><?php echo htmlspecialchars($valor['observaciones_clasificacion'] ?: ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Calidad</button>
                    <a href="index.php?opcion=mostrar_calidad" class="btn btn-danger">Salir</a>
                </div>
                
                <script>
                // Validación de campos en edición
                document.querySelector('form').addEventListener('submit', function(e) {
                    var numeroLote = document.getElementsByName('numero_lote')[0].value;
                    var uso = document.getElementsByName('uso')[0].value;
                    
                    if (!numeroLote.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'El número de lote es requerido'
                        });
                        return false;
                    }
                    
                    if (!uso.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'El uso es requerido'
                        });
                        return false;
                    }
                    
                    var primera = document.getElementsByName('primera_calidad')[0].value;
                    var segunda = document.getElementsByName('segunda_calidad')[0].value;
                    var descarte = document.getElementsByName('descarte')[0].value;
                    
                    if (primera < 0 || segunda < 0 || descarte < 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Los valores no pueden ser negativos'
                        });
                        return false;
                    }
                });
                </script>
                <?php
            }
        } 
    }

    // Editar solo revisión de calidad (compatibilidad)
    static public function editarRevisionCalidadControlador()
    {
        if (isset($_POST['pk'])) {
            $pk = $_POST['pk'];
            $respuesta = ModeloCalidad::editarRevisionCalidadModelo($pk);
            
            if ($respuesta && count($respuesta) > 0) {
                $valor = $respuesta[0];
                ?>
                <!-- Llegada -->
                <div class="mb-3">
                    <label class="form-label">Llegada:</label>
                    <select name="fk_llegada" class="form-control" required>
                        <?php
                        $llegadas = ModeloCalidad::cargarLlegadasModelo();
                        foreach ($llegadas as $llegada) {
                            $selected = ($llegada['pk_llegada'] == $valor['fk_llegada']) ? 'selected' : '';
                            echo '<option value="' . $llegada['pk_llegada'] . '" ' . $selected . '>Llegada #' . $llegada['pk_llegada'] . ' - ' . $llegada['fecha'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- Madurez -->
                <div class="mb-3">
                    <label class="form-label">Madurez:</label>
                    <select name="madurez" class="form-control" required>
                        <option value="Verde" <?php echo ($valor['madurez'] == 'Verde') ? 'selected' : ''; ?>>Verde</option>
                        <option value="Maduro" <?php echo ($valor['madurez'] == 'Maduro') ? 'selected' : ''; ?>>Maduro</option>
                        <option value="Muy Maduro" <?php echo ($valor['madurez'] == 'Muy Maduro') ? 'selected' : ''; ?>>Muy Maduro</option>
                    </select>
                </div>
                <!-- Plagas -->
                <div class="mb-3">
                    <label class="form-label">Plagas:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="plagas" id="plagas_si_simple" value="1" 
                                <?php echo ($valor['plagas'] == 1) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="plagas_si_simple">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="plagas" id="plagas_no_simple" value="0" 
                                <?php echo ($valor['plagas'] == 0) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="plagas_no_simple">No</label>
                        </div>
                    </div>
                </div>
                <!-- Daños -->
                <div class="mb-3">
                    <label class="form-label">Daños:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="danos" id="danos_si_simple" value="1" 
                                <?php echo ($valor['danos'] == 1) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="danos_si_simple">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="danos" id="danos_no_simple" value="0" 
                                <?php echo ($valor['danos'] == 0) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="danos_no_simple">No</label>
                        </div>
                    </div>
                </div>
                <!-- Contaminantes -->
                <div class="mb-3">
                    <label class="form-label">Contaminantes:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_si_simple" value="1" 
                                <?php echo ($valor['contaminantes'] == 1) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="contaminantes_si_simple">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_no_simple" value="0" 
                                <?php echo ($valor['contaminantes'] == 0) ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="contaminantes_no_simple">No</label>
                        </div>
                    </div>
                </div>
                <!-- Observaciones -->
                <div class="mb-3">
                    <label class="form-label">Observaciones:</label>
                    <textarea name="observaciones" class="form-control" rows="3"><?php echo htmlspecialchars($valor['observaciones'] ?: ''); ?></textarea>
                </div>
                
                <input type="hidden" name="pk_revision" value="<?php echo $valor['pk_revision']; ?>" />
                
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="submit">Actualizar Revisión</button>
                    <a href="index.php?opcion=mostrar_calidad" class="btn btn-danger">Salir</a>
                </div>
                <?php
            }
        }
    }

    // Actualizar calidad completa
    static public function actualizarCalidadCompletaControlador()
    {
        if (isset($_POST["fk_llegada"], $_POST["madurez"], $_POST["plagas"], $_POST["danos"], 
                  $_POST["contaminantes"], $_POST["numero_lote"], $_POST["uso"], 
                  $_POST["pk_revision"], $_POST["pk_lote"], $_POST["pk_clasificacion"])) {
            
            // Verificar si el lote ya existe (excluyendo el actual)
            $loteExistente = ModeloCalidad::verificarLoteExistenteModelo($_POST['numero_lote'], $_POST['fk_llegada'], $_POST['pk_lote']);
            
            if ($loteExistente) {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El número de lote ya existe para esta llegada'
                    });
                </script>
                <?php
                return;
            }
            
            $datosRevision = array(
                "fk_llegada" => $_POST['fk_llegada'],
                "madurez" => $_POST['madurez'],
                "plagas" => $_POST['plagas'],
                "danos" => $_POST['danos'],
                "contaminantes" => $_POST['contaminantes'],
                "observaciones" => $_POST['observaciones_revision'],
                "pk_revision" => $_POST['pk_revision']
            );
            
            $datosLote = array(
                "fk_llegada" => $_POST['fk_llegada'],
                "numero_lote" => $_POST['numero_lote'],
                "variedad" => $_POST['variedad'],
                "pk_lote" => $_POST['pk_lote']
            );
            
            $datosClasificacion = array(
                "primera_calidad" => $_POST['primera_calidad'] ?: null,
                "segunda_calidad" => $_POST['segunda_calidad'] ?: null,
                "descarte" => $_POST['descarte'] ?: null,
                "uso" => $_POST['uso'],
                "observaciones" => $_POST['observaciones_clasificacion'],
                "pk_clasificacion" => $_POST['pk_clasificacion']
            );

            $respuesta = ModeloCalidad::actualizarCalidadCompletaModelo($datosRevision, $datosLote, $datosClasificacion);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Calidad actualizada!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Crear formulario oculto para forzar POST a mostrar_calidad
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'opcion';
                        input.value = 'mostrar_calidad';

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

    // Actualizar solo revisión de calidad (compatibilidad)
    static public function actualizarRevisionCalidadControlador()
    {
        if (isset($_POST["fk_llegada"], $_POST["madurez"], $_POST["plagas"], $_POST["danos"], 
                  $_POST["contaminantes"], $_POST["pk_revision"])) {
            
            $datosRevision = array(
                "fk_llegada" => $_POST['fk_llegada'],
                "madurez" => $_POST['madurez'],
                "plagas" => $_POST['plagas'],
                "danos" => $_POST['danos'],
                "contaminantes" => $_POST['contaminantes'],
                "observaciones" => $_POST['observaciones'],
                "pk_revision" => $_POST['pk_revision']
            );

            $respuesta = ModeloCalidad::actualizarRevisionCalidadModelo($datosRevision);

            if ($respuesta == 'ok') {
                ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Revisión actualizada!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'index.php?opcion=mostrar_calidad';
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