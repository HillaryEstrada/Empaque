<?php
// mostrar_llegada_mango.php
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

/* Efectos de loading */
.loading-spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
    display: inline-block;
    margin-left: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
                    echo ($estado == 1) ? "Mostrar Llegadas de Mango" : "Papelera Llegadas de Mango";
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
                    <p class="text-muted">Datos principales de la llegada</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Rancho:</label>
                    <select name="fk_rancho" class="form-control" required onchange="animateFieldCompletion(this)">
                        <option value="">Seleccionar rancho...</option>
                        <?php
                        // Cargar ranchos activos
                        $llegadas = new ControladorLlegada();
                        $llegadas->cargarRanchosControlador();
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Usuario:</label>
                    <select name="fk_usuario" class="form-control" required onchange="animateFieldCompletion(this)">
                        <option value="">Seleccionar usuario...</option>
                        <?php
                        // Cargar usuarios activos
                        $llegadas->cargarUsuariosControlador();
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tipo de Llegada:</label>
                    <select name="tipo_llegada" class="form-control" required onchange="animateFieldCompletion(this)">
                        <option value="">Seleccionar tipo...</option>
                        <option value="Compra Directa">Compra Directa</option>
                        <option value="Consignación">Consignación</option>
                        <option value="Maquila">Maquila</option>
                    </select>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-danger" onclick="ocultarFormulario()">Salir</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                </div>
            </div>

            <!-- PASO 2: Detalles de Transporte -->
            <div class="step-card" id="step-2">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Detalles de Transporte</h3>
                    <p class="text-muted">Información del envío y responsable</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Medio de Transporte:</label>
                    <input type="text" name="medio_transporte" class="form-control" 
                           placeholder="Ej: Camión, Pickup, etc." required />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tipo de Envase:</label>
                    <input type="text" name="tipo_envase" class="form-control" 
                           placeholder="Ej: Cajas de cartón, costales, etc." required />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Responsable:</label>
                    <input type="text" name="responsable" class="form-control" 
                           placeholder="Nombre del responsable" required />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Observaciones:</label>
                    <textarea name="observaciones" class="form-control" rows="3" 
                              placeholder="Observaciones adicionales"></textarea>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>
                    </div>
                </div>
            </div>

            <!-- PASO 3: Datos de Pesaje -->
            <div class="step-card" id="step-3">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Datos de Pesaje</h3>
                    <p class="text-muted">Información de pesos</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Peso Bruto (kg):</label>
                    <input type="number" name="peso_bruto" class="form-control" 
                           step="0.01" min="0" placeholder="0.00" required 
                           onchange="calcularPesoNeto()" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Peso Envase (kg):</label>
                    <input type="number" name="peso_envase" class="form-control" 
                           step="0.01" min="0" placeholder="0.00" required 
                           onchange="calcularPesoNeto()" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Peso Neto (kg):</label>
                    <input type="number" name="peso_neto" class="form-control calculation-display" 
                           step="0.01" min="0" placeholder="0.00" readonly />
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">Siguiente</button>
                    </div>
                </div>
            </div>

            <!-- PASO 4: Datos de Compra -->
            <div class="step-card" id="step-4">
                <div class="step-header">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Datos de Compra</h3>
                    <p class="text-muted">Información financiera</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Precio por Kilo ($):</label>
                    <input type="number" name="precio_kilo" class="form-control" 
                           step="0.01" min="0" placeholder="0.00" required 
                           onchange="calcularTotal()" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Total a Pagar ($):</label>
                    <input type="number" name="total_pagado" class="form-control calculation-display" 
                           step="0.01" min="0" placeholder="0.00" readonly />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Método de Pago:</label>
                    <select name="metodo_pago" class="form-control" required>
                        <option value="">Seleccionar método...</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Crédito">Crédito</option>
                    </select>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(5)">Revisar</button>
                    </div>
                </div>
            </div>

            <!-- PASO 5: Confirmación -->
            <div class="step-card" id="step-5">
                <div class="step-header">
                    <div class="step-number">✓</div>
                    <h3 class="step-title">Confirmar Información</h3>
                    <p class="text-muted">Revise los datos antes de guardar</p>
                </div>
                
                <div id="summary-content">
                    <!-- El resumen se llenará dinámicamente -->
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(4)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="submit" class="btn btn-success">Guardar Llegada</button>
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
            <th>Fecha</th>
            <th>Rancho</th>
            <th>Usuario</th>
            <th>Tipo Llegada</th>
            <th>Medio Transporte</th>
            <th>Responsable</th>
            <th>Peso Neto (kg)</th>
            <th>Total Pagado ($)</th>
            <th>Método Pago</th>
            <th>
                <?php if ($estado == 1): ?>
                <button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" data-title="Alta llegada de mango">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Nueva Llegada
                </button>
                <?php endif; ?>
                
                <!-- Botón para alternar entre activos e inactivos -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <input type="hidden" name="opcion" value="mostrar_llegada_mango">
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
            $llegada = new ControladorLlegada();
            $llegada->mostrarLlegadasCompletasControlador($estado);
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
const totalSteps = 5;

function nextStep(step) {
    if (validateCurrentStep()) {
        showStep(step);
        updateProgress();
    }
}

function prevStep(step) {
    showStep(step);
    updateProgress();
}

function showStep(step) {
    // Ocultar step actual
    document.getElementById('step-' + currentStep).classList.remove('active');
    
    // Mostrar nuevo step
    currentStep = step;
    document.getElementById('step-' + currentStep).classList.add('active');
    
    // Si es el último paso, generar resumen
    if (step === 5) {
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
        if (!field.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor complete todos los campos antes de continuar.'
            });
            field.focus();
            return false;
        }
    }
    
    // Validación especial para peso en step 3
    if (currentStep === 3) {
        const pesoBruto = parseFloat(document.getElementsByName('peso_bruto')[0].value) || 0;
        const pesoEnvase = parseFloat(document.getElementsByName('peso_envase')[0].value) || 0;
        
        if (pesoBruto <= pesoEnvase) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el peso',
                text: 'El peso bruto debe ser mayor al peso del envase'
            });
            return false;
        }
    }
    
    return true;
}

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

function generateSummary() {
    const form = document.getElementById('form-alta');
    const formData = new FormData(form);
    
    const summaryContent = document.getElementById('summary-content');
    summaryContent.innerHTML = `
        <div class="summary-item" style="animation-delay: 0.1s">
            <span>🏠 Rancho:</span>
            <span>${form.fk_rancho.selectedOptions[0]?.text || 'No seleccionado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.2s">
            <span>👤 Usuario:</span>
            <span>${form.fk_usuario.selectedOptions[0]?.text || 'No seleccionado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.3s">
            <span>🏷️ Tipo de Llegada:</span>
            <span>${formData.get('tipo_llegada') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.4s">
            <span>🚛 Medio de Transporte:</span>
            <span>${formData.get('medio_transporte') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.5s">
            <span>📦 Tipo de Envase:</span>
            <span>${formData.get('tipo_envase') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.6s">
            <span>👨‍💼 Responsable:</span>
            <span>${formData.get('responsable') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.7s">
            <span>⚖️ Peso Neto:</span>
            <span>${formData.get('peso_neto') || '0'} kg</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.8s">
            <span>💰 Precio por Kilo:</span>
            <span>${formData.get('precio_kilo') || '0.00'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.9s">
            <span>💳 Método de Pago:</span>
            <span>${form.metodo_pago.selectedOptions[0]?.text || 'No seleccionado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 1s">
            <span>💵 TOTAL A PAGAR:</span>
            <span>${formData.get('total_pagado') || '0.00'}</span>
        </div>
    `;
}

function calcularPesoNeto() {
    var pesoBruto = parseFloat(document.getElementsByName('peso_bruto')[0].value) || 0;
    var pesoEnvase = parseFloat(document.getElementsByName('peso_envase')[0].value) || 0;
    var pesoNeto = pesoBruto - pesoEnvase;
    
    if (pesoNeto < 0) pesoNeto = 0;
    
    document.getElementsByName('peso_neto')[0].value = pesoNeto.toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    var pesoNeto = parseFloat(document.getElementsByName('peso_neto')[0].value) || 0;
    var precioKilo = parseFloat(document.getElementsByName('precio_kilo')[0].value) || 0;
    var total = pesoNeto * precioKilo;
    
    document.getElementsByName('total_pagado')[0].value = total.toFixed(2);
}

// Validación final del formulario
document.getElementById('form-alta').addEventListener('submit', function(e) {
    // Tu validación existente se mantiene igual
    var pesoBruto = parseFloat(document.getElementsByName('peso_bruto')[0].value) || 0;
    var pesoEnvase = parseFloat(document.getElementsByName('peso_envase')[0].value) || 0;
    
    if (pesoBruto <= pesoEnvase) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El peso bruto debe ser mayor al peso del envase'
        });
        return false;
    }
});

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
    currentStep = 1;
}
</script>

<?php
// Tu código PHP existente se mantiene exactamente igual
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['fk_rancho']) && !empty($_POST['fk_usuario']) && !empty($_POST['tipo_llegada']) && 
        !empty($_POST['medio_transporte']) && !empty($_POST['tipo_envase']) && !empty($_POST['responsable']) &&
        !empty($_POST['peso_bruto']) && !empty($_POST['peso_envase']) && !empty($_POST['peso_neto']) &&
        !empty($_POST['precio_kilo']) && !empty($_POST['total_pagado']) && !empty($_POST['metodo_pago'])) {
        
        $datosLlegada = array(
            "fk_rancho" => htmlspecialchars($_POST['fk_rancho']),
            "fk_usuario" => htmlspecialchars($_POST['fk_usuario']),
            "tipo_llegada" => htmlspecialchars($_POST['tipo_llegada'])
        );

        $datosDetalle = array(
            "medio_transporte" => htmlspecialchars($_POST['medio_transporte']),
            "tipo_envase" => htmlspecialchars($_POST['tipo_envase']),
            "responsable" => htmlspecialchars($_POST['responsable']),
            "observaciones" => htmlspecialchars($_POST['observaciones'])
        );

        $datosPesaje = array(
            "peso_bruto" => htmlspecialchars($_POST['peso_bruto']),
            "peso_envase" => htmlspecialchars($_POST['peso_envase']),
            "peso_neto" => htmlspecialchars($_POST['peso_neto'])
        );

        $datosCompra = array(
            "precio_kilo" => htmlspecialchars($_POST['precio_kilo']),
            "total_pagado" => htmlspecialchars($_POST['total_pagado']),
            "metodo_pago" => htmlspecialchars($_POST['metodo_pago'])
        );

        $registro = new ControladorLlegada();
        $registro->registroLlegadaCompletaControlador($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra);
    }
}
?>