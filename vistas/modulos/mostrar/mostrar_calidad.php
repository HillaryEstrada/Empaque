<?php
// mostrar_calidad.php
?>
<style>
.step-container {
    max-width: 1000px;
    margin: 0 auto;
}

.step-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 1rem;
    display: none;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.step-card.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.step-header {
    text-align: center;
    margin-bottom: 2rem;
}

.step-number {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    line-height: 40px;
    font-weight: bold;
    margin-bottom: 1rem;
}

.step-title {
    color: #495057;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    width: 100%;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 1rem;
    background: #fafafa;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.25rem rgba(0,123,255,0.25);
    outline: none;
    background: white;
    transform: translateY(-2px);
}

.form-control:hover {
    border-color: #80bdff;
    background: white;
}

.calculation-display {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 2px solid #2196f3;
    color: #1565c0;
    font-weight: bold;
    font-size: 1.2rem;
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from { box-shadow: 0 0 5px rgba(33, 150, 243, 0.5); }
    to { box-shadow: 0 0 20px rgba(33, 150, 243, 0.8); }
}

.btn-group-nav {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    gap: 1rem;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 1.1rem;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn:active {
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    box-shadow: 0 8px 25px rgba(0,123,255,0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #545b62);
    color: white;
    box-shadow: 0 4px 15px rgba(108,117,125,0.3);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
    box-shadow: 0 4px 15px rgba(40,167,69,0.3);
    animation: pulse-success 2s infinite;
}

@keyframes pulse-success {
    0% { box-shadow: 0 4px 15px rgba(40,167,69,0.3); }
    50% { box-shadow: 0 6px 20px rgba(40,167,69,0.6); }
    100% { box-shadow: 0 4px 15px rgba(40,167,69,0.3); }
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    box-shadow: 0 4px 15px rgba(220,53,69,0.3);
}

.progress-bar-container {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 10px;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #007bff, #0056b3, #007bff);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 10px;
    position: relative;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: sweep 3s infinite;
}

@keyframes sweep {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    transition: all 0.3s ease;
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
    transform: translateY(20px);
}

.summary-item:hover {
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    transform: translateX(5px);
}

.summary-item:last-child {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.3rem;
    color: #28a745;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    box-shadow: 0 4px 15px rgba(40,167,69,0.2);
    animation: pulse-total 2s infinite;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse-total {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Efectos para campos completados */
.form-control.completed {
    border-color: #28a745;
    background: linear-gradient(135deg, #d4edda, #ffffff);
}

.form-control.completed::after {
    content: '✓';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #28a745;
    font-weight: bold;
}

/* Indicador de paso completado */
.step-number.completed {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    animation: bounce 1s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Radio buttons mejorados */
.form-check {
    margin-bottom: 0.5rem;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-label {
    font-weight: 500;
    margin-left: 0.5rem;
}

.radio-group {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.radio-option {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: #fafafa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.radio-option:hover {
    border-color: #80bdff;
    background: white;
}

.radio-option.selected {
    border-color: #007bff;
    background: linear-gradient(135deg, #e3f2fd, #ffffff);
}

@media (max-width: 768px) {
    .step-container {
        max-width: 100%;
        padding: 0 1rem;
    }
    
    .step-card {
        padding: 1.5rem;
    }
    
    .btn-group-nav {
        flex-direction: column;
    }
    
    .radio-group {
        flex-direction: column;
    }
}
</style>

<div class="mango-container"> 
    <div align="center">
        <!-- Mostrar la alerta SweetAlert si existe -->
        <?php if (isset($_POST['alerta'])): ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '<?php echo ($_POST['alerta'] === 'activado') ? "Elemento activado correctamente" : "Elemento desactivado correctamente"; ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        <?php endif; ?>

        <!-- Aquí sí va el encabezado visual de la sección -->
        <div class="alert alert-primary mt-5" role="alert">
            <h1 id="titulo">
                <?php
                    $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
                    echo ($estado == 1) ? "Mostrar Calidad Completa" : "Papelera Calidad Completa";
                ?>
            </h1>
        </div>
    </div>
</div>

<!--Inicio del Proceso de Alta-->
<div id="formulario-alta" style="display: none;">
    <div class="step-container">
        <!-- Barra de Progreso -->
        <div class="progress-bar-container">
            <div class="progress-bar" id="progress-bar" style="width: 25%"></div>
        </div>

        <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
            
            <!-- PASO 1: Información Básica -->
            <div class="step-card active" id="step-1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Información Básica</h3>
                    <p class="text-muted">Seleccione la llegada a revisar</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Llegada:</label>
                    <select name="fk_llegada" class="form-control" required onchange="animateFieldCompletion(this)">
                        <option value="">Seleccionar llegada...</option>
                        <?php
                        // Cargar llegadas activas
                        $calidad = new ControladorCalidad();
                        $calidad->cargarLlegadasControlador();
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Número de Lote:</label>
                    <input type="text" name="numero_lote" class="form-control" 
                           placeholder="Ingrese el número del lote" required 
                           onchange="animateFieldCompletion(this)" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Variedad:</label>
                    <input type="text" name="variedad" class="form-control" 
                           placeholder="Variedad del mango (opcional)" 
                           onchange="animateFieldCompletion(this)" />
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-danger" onclick="ocultarFormulario()">Salir</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                </div>
            </div>

            <!-- PASO 2: Revisión de Calidad -->
            <div class="step-card" id="step-2">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Revisión de Calidad</h3>
                    <p class="text-muted">Evaluación del estado del producto</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Madurez:</label>
                    <select name="madurez" class="form-control" required onchange="animateFieldCompletion(this)">
                        <option value="">Seleccionar madurez...</option>
                        <option value="Verde">🟢 Verde</option>
                        <option value="Maduro">🟡 Maduro</option>
                        <option value="Muy Maduro">🟠 Muy Maduro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Plagas:</label>
                    <div class="radio-group">
                        <div class="radio-option" onclick="selectRadio('plagas', '1', this)">
                            <input class="form-check-input" type="radio" name="plagas" id="plagas_si" value="1" required>
                            <label class="form-check-label" for="plagas_si">🐛 Sí</label>
                        </div>
                        <div class="radio-option" onclick="selectRadio('plagas', '0', this)">
                            <input class="form-check-input" type="radio" name="plagas" id="plagas_no" value="0" required>
                            <label class="form-check-label" for="plagas_no">✅ No</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Daños:</label>
                    <div class="radio-group">
                        <div class="radio-option" onclick="selectRadio('danos', '1', this)">
                            <input class="form-check-input" type="radio" name="danos" id="danos_si" value="1" required>
                            <label class="form-check-label" for="danos_si">⚠️ Sí</label>
                        </div>
                        <div class="radio-option" onclick="selectRadio('danos', '0', this)">
                            <input class="form-check-input" type="radio" name="danos" id="danos_no" value="0" required>
                            <label class="form-check-label" for="danos_no">✅ No</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contaminantes:</label>
                    <div class="radio-group">
                        <div class="radio-option" onclick="selectRadio('contaminantes', '1', this)">
                            <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_si" value="1" required>
                            <label class="form-check-label" for="contaminantes_si">☣️ Sí</label>
                        </div>
                        <div class="radio-option" onclick="selectRadio('contaminantes', '0', this)">
                            <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes_no" value="0" required>
                            <label class="form-check-label" for="contaminantes_no">✅ No</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Observaciones Revisión:</label>
                    <textarea name="observaciones_revision" class="form-control" rows="3" 
                              placeholder="Observaciones de la revisión..."
                              onchange="animateFieldCompletion(this)"></textarea>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>
                    </div>
                </div>
            </div>

            <!-- PASO 3: Clasificación -->
            <div class="step-card" id="step-3">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Clasificación</h3>
                    <p class="text-muted">Distribución por calidades</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Primera Calidad (kg):</label>
                            <input type="number" name="primera_calidad" class="form-control" 
                                   step="0.01" min="0" placeholder="0.00" 
                                   onchange="animateFieldCompletion(this); calcularTotal()" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Segunda Calidad (kg):</label>
                            <input type="number" name="segunda_calidad" class="form-control" 
                                   step="0.01" min="0" placeholder="0.00" 
                                   onchange="animateFieldCompletion(this); calcularTotal()" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Descarte (kg):</label>
                            <input type="number" name="descarte" class="form-control" 
                                   step="0.01" min="0" placeholder="0.00" 
                                   onchange="animateFieldCompletion(this); calcularTotal()" />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Total Clasificado:</label>
                    <input type="number" id="total_clasificado" class="form-control calculation-display" 
                           step="0.01" min="0" placeholder="0.00" readonly />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Uso:</label>
                    <input type="text" name="uso" class="form-control" 
                           placeholder="Destino o uso del producto" required 
                           onchange="animateFieldCompletion(this)" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Observaciones Clasificación:</label>
                    <textarea name="observaciones_clasificacion" class="form-control" rows="3" 
                              placeholder="Observaciones de la clasificación..."
                              onchange="animateFieldCompletion(this)"></textarea>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">Revisar</button>
                    </div>
                </div>
            </div>

            <!-- PASO 4: Confirmación -->
            <div class="step-card" id="step-4">
                <div class="step-header">
                    <div class="step-number">✓</div>
                    <h3 class="step-title">Confirmar Información</h3>
                    <p class="text-muted">Revise los datos antes de guardar</p>
                </div>
                
                <div id="summary-content">
                    <!-- El resumen se llenará dinámicamente -->
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="submit" class="btn btn-success">Guardar Calidad</button>
                    </div>
                </div>
            </div>
            
            <!-- Campos hidden -->
            <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        </form>
    </div>
</div>
<!--Fin del Proceso de Alta-->

<!--Inicio Proceso de Mostrar-->
<div id="tabla-catalogo" class="w-100">
<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th>Llegada</th>
            <th>Madurez</th>
            <th>Plagas</th>
            <th>Daños</th>
            <th>Contaminantes</th>
            <th>Lote</th>
            <th>Variedad</th>
            <th>Primera</th>
            <th>Segunda</th>
            <th>Descarte</th>
            <th>Uso</th>
            <th>
                <?php if ($estado == 1): ?>
                <button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" data-title="Alta calidad completa">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear Calidad
                </button>
                <?php endif; ?>
                
                <!-- Botón para alternar entre activos e inactivos -->
                <form action="index.php" method="POST" style="display: inline;">
                    <input type="hidden" name="opcion" value="mostrar_calidad">
                    <input type="hidden" name="estado" value="<?php echo ($estado == 1) ? 0 : 1; ?>">
                    <button type="submit" class="btn btn-sm <?php echo ($estado == 1) ? 'btn-warning' : 'btn-primary'; ?>">
                        <i class="fa-solid <?php echo ($estado == 1) ? 'fa-archive' : 'fa-undo'; ?>"></i>
                        <?php echo ($estado == 1) ? 'Ver Inactivos' : 'Ver Activos'; ?>
                    </button> 
                </form>
            </th>
        </tr>
    </thead>

    <tbody id="tabla-body">
        <?php
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
            $calidad = new ControladorCalidad();
            $calidad->mostrarCalidadCompletaControlador($estado);
        ?>
    </tbody>
</table>

        <!-- Contenedor de paginación con contador de registros -->
        <div id="paginacion-container" class="pagination-container text-center mt-3">
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-primary me-2" id="btn-primero" disabled>Primero</button>
                <button class="btn btn-primary me-2" id="btn-anterior" disabled>Anterior</button>
                <span id="pagina-info" class="mx-3">Página 1</span>
                <input type="text" id="pagina-input" value="1" style="width: 40px; text-align: center;">
                <span id="registro-info" class="mx-3"></span>
                <button class="btn btn-primary ms-2" id="btn-siguiente">Siguiente</button>
                <button class="btn btn-primary ms-2" id="btn-ultimo">Último</button>
            </div>
        </div>
</div>
<!--Fin del Proceso de Mostrar-->

<!-- Scripts para navegación y cálculos -->
<script>
let currentStep = 1;
const totalSteps = 4;

function nextStep(step) {
    if (validateCurrentStep()) {
        // Marcar paso actual como completado
        const currentStepNumber = document.querySelector('#step-' + currentStep + ' .step-number');
        currentStepNumber.classList.add('completed');
        
        // Efecto de transición suave
        const currentStepCard = document.getElementById('step-' + currentStep);
        currentStepCard.style.transform = 'translateX(-100%)';
        currentStepCard.style.opacity = '0';
        
        setTimeout(() => {
            showStep(step);
            updateProgress();
            
            // Efecto de entrada
            const newStepCard = document.getElementById('step-' + step);
            newStepCard.style.transform = 'translateX(100%)';
            newStepCard.style.opacity = '0';
            
            setTimeout(() => {
                newStepCard.style.transform = 'translateX(0)';
                newStepCard.style.opacity = '1';
            }, 50);
        }, 300);
        
        // Sonido de éxito (opcional)
        playSuccessSound();
    }
}

function prevStep(step) {
    const currentStepCard = document.getElementById('step-' + currentStep);
    currentStepCard.style.transform = 'translateX(100%)';
    currentStepCard.style.opacity = '0';
    
    setTimeout(() => {
        showStep(step);
        updateProgress();
        
        const newStepCard = document.getElementById('step-' + step);
        newStepCard.style.transform = 'translateX(-100%)';
        newStepCard.style.opacity = '0';
        
        setTimeout(() => {
            newStepCard.style.transform = 'translateX(0)';
            newStepCard.style.opacity = '1';
        }, 50);
    }, 300);
}

function showStep(step) {
    // Ocultar step actual
    document.getElementById('step-' + currentStep).classList.remove('active');
    
    // Mostrar nuevo step
    currentStep = step;
    document.getElementById('step-' + currentStep).classList.add('active');
    
    // Si es el último paso, generar resumen
    if (step === 4) {
        generateSummary();
    }
}

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progress-bar').style.width = progress + '%';
}

function validateCurrentStep() {
    const currentStepElement = document.getElementById('step-' + currentStep);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (field.type === 'radio') {
            const radioGroup = currentStepElement.querySelectorAll(`input[name="${field.name}"]`);
            const isChecked = Array.from(radioGroup).some(radio => radio.checked);
            if (!isChecked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor seleccione una opción para ' + field.name.replace('_', ' ')
                });
                return false;
            }
        } else if (!field.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor complete todos los campos antes de continuar.'
            });
            field.focus();
            return false;
        }
    }
    
    return true;
}

function selectRadio(name, value, element) {
    // Desmarcar todas las opciones del grupo
    const allOptions = document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
        radio.closest('.radio-option').classList.remove('selected');
    });
    
    // Marcar la opción seleccionada
    element.classList.add('selected');
    element.querySelector('input').checked = true;
    
    // Efecto de animación
    createParticleEffect(element);
}

function animateFieldCompletion(field) {
    if (field.value.trim() !== '') {
        field.classList.add('completed');
        
        // Efecto de partículas (simulado)
        createParticleEffect(field);
        
        // Vibración suave
        field.style.animation = 'bounce 0.5s ease-in-out';
        setTimeout(() => {
            field.style.animation = '';
        }, 500);
    } else {
        field.classList.remove('completed');
    }
}

function createParticleEffect(element) {
    for (let i = 0; i < 5; i++) {
        const particle = document.createElement('div');
        particle.style.position = 'absolute';
        particle.style.width = '4px';
        particle.style.height = '4px';
        particle.style.background = '#28a745';
        particle.style.borderRadius = '50%';
        particle.style.pointerEvents = 'none';
        particle.style.animation = `particleFloat 1s ease-out forwards`;
        
        const rect = element.getBoundingClientRect();
        particle.style.left = (rect.left + Math.random() * rect.width) + 'px';
        particle.style.top = (rect.top + rect.height/2) + 'px';
        
        document.body.appendChild(particle);
        
        setTimeout(() => {
            particle.remove();
        }, 1000);
    }
}

// Agregar estilos de partículas dinámicamente
const particleStyles = `
@keyframes particleFloat {
    0% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(-30px) scale(0);
    }
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = particleStyles;
document.head.appendChild(styleSheet);

function playSuccessSound() {
    // Crear un sonido usando Web Audio API
    if (typeof(AudioContext) !== "undefined" || typeof(webkitAudioContext) !== "undefined") {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(1200, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }
}

function calcularTotal() {
    var primera = parseFloat(document.getElementsByName('primera_calidad')[0].value) || 0;
    var segunda = parseFloat(document.getElementsByName('segunda_calidad')[0].value) || 0;
    var descarte = parseFloat(document.getElementsByName('descarte')[0].value) || 0;
    var total = primera + segunda + descarte;
    
    document.getElementById('total_clasificado').value = total.toFixed(2);
}

function generateSummary() {
    const form = document.getElementById('form-alta');
    const formData = new FormData(form);
    
    // Obtener valores de radio buttons
    const plagas = form.querySelector('input[name="plagas"]:checked')?.value;
    const danos = form.querySelector('input[name="danos"]:checked')?.value;
    const contaminantes = form.querySelector('input[name="contaminantes"]:checked')?.value;
    
    const summaryContent = document.getElementById('summary-content');
    summaryContent.innerHTML = `
        <div class="summary-item" style="animation-delay: 0.1s">
            <span>📦 Llegada:</span>
            <span>${form.fk_llegada.selectedOptions[0]?.text || 'No seleccionada'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.2s">
            <span>🏷️ Número de Lote:</span>
            <span>${formData.get('numero_lote') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.3s">
            <span>🥭 Variedad:</span>
            <span>${formData.get('variedad') || 'No especificada'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.4s">
            <span>🔄 Madurez:</span>
            <span>${formData.get('madurez') || 'No especificada'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.5s">
            <span>🐛 Plagas:</span>
            <span>${plagas === '1' ? '❌ Sí' : plagas === '0' ? '✅ No' : 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.6s">
            <span>⚠️ Daños:</span>
            <span>${danos === '1' ? '❌ Sí' : danos === '0' ? '✅ No' : 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.7s">
            <span>☣️ Contaminantes:</span>
            <span>${contaminantes === '1' ? '❌ Sí' : contaminantes === '0' ? '✅ No' : 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.8s">
            <span>🥇 Primera Calidad:</span>
            <span>${formData.get('primera_calidad') || '0'} kg</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.9s">
            <span>🥈 Segunda Calidad:</span>
            <span>${formData.get('segunda_calidad') || '0'} kg</span>
        </div>
        <div class="summary-item" style="animation-delay: 1s">
            <span>🗑️ Descarte:</span>
            <span>${formData.get('descarte') || '0'} kg</span>
        </div>
        <div class="summary-item" style="animation-delay: 1.1s">
            <span>🎯 Uso:</span>
            <span>${formData.get('uso') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 1.2s">
            <span>📊 TOTAL CLASIFICADO:</span>
            <span>${document.getElementById('total_clasificado').value || '0.00'} kg</span>
        </div>
    `;
}

function mostrarFormulario() {
    document.getElementById('formulario-alta').style.display = 'block';
    document.getElementById('tabla-catalogo').style.display = 'none';
    currentStep = 1;
    showStep(1);
    updateProgress();
}

function ocultarFormulario() {
    document.getElementById('formulario-alta').style.display = 'none';
    document.getElementById('tabla-catalogo').style.display = 'block';
    document.getElementById('form-alta').reset();
    
    // Limpiar selecciones de radio buttons
    document.querySelectorAll('.radio-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    currentStep = 1;
}

// Validación final del formulario
document.getElementById('form-alta').addEventListener('submit', function(e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
        return false;
    }
    
    var fk_llegada = document.getElementsByName('fk_llegada')[0].value;
    var numero_lote = document.getElementsByName('numero_lote')[0].value;
    var uso = document.getElementsByName('uso')[0].value;
    
    if (!fk_llegada) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe seleccionar una llegada'
        });
        return false;
    }
    
    if (!numero_lote.trim()) {
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
});
</script>

<?php
// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el formulario tiene los datos necesarios para calidad completa
    if (isset($_POST['fk_llegada']) && !empty($_POST['madurez']) && isset($_POST['plagas']) && 
        isset($_POST['danos']) && isset($_POST['contaminantes']) && !empty($_POST['numero_lote']) && 
        !empty($_POST['uso'])) {
        
        // Recoger datos del formulario
        $fk_llegada = htmlspecialchars($_POST['fk_llegada']);
        $madurez = htmlspecialchars($_POST['madurez']); 
        $plagas = htmlspecialchars($_POST['plagas']); 
        $danos = htmlspecialchars($_POST['danos']); 
        $contaminantes = htmlspecialchars($_POST['contaminantes']); 
        $observaciones_revision = htmlspecialchars($_POST['observaciones_revision']); 
        $numero_lote = htmlspecialchars($_POST['numero_lote']); 
        $variedad = htmlspecialchars($_POST['variedad']); 
        $primera_calidad = htmlspecialchars($_POST['primera_calidad']); 
        $segunda_calidad = htmlspecialchars($_POST['segunda_calidad']); 
        $descarte = htmlspecialchars($_POST['descarte']); 
        $uso = htmlspecialchars($_POST['uso']); 
        $observaciones_clasificacion = htmlspecialchars($_POST['observaciones_clasificacion']); 

        // Llamar al controlador para manejar el registro completo
        $registro = new ControladorCalidad();
        $registro->registroCalidadCompletaControlador($fk_llegada, $madurez, $plagas, $danos, $contaminantes, 
                                                     $observaciones_revision, $numero_lote, $variedad, 
                                                     $primera_calidad, $segunda_calidad, $descarte, $uso, 
                                                     $observaciones_clasificacion);
    }
}
?>