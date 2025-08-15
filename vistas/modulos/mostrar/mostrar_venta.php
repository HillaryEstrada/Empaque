<?php
// mostrar_venta.php
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

.currency-input {
    position: relative;
}

.currency-input::before {
    content: '$';
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #28a745;
    font-weight: bold;
    font-size: 1.2rem;
    z-index: 10;
}

.currency-input input {
    padding-left: 2.5rem;
}

.ingreso-display {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border: 2px solid #28a745;
    color: #155724;
    font-weight: bold;
    font-size: 1.3rem;
    animation: glow-green 2s ease-in-out infinite alternate;
}

@keyframes glow-green {
    from { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
    to { box-shadow: 0 0 20px rgba(40, 167, 69, 0.8); }
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

/* Destacar campos importantes */
.client-input {
    border-color: #17a2b8;
    background: linear-gradient(135deg, #d1ecf1, #ffffff);
}

.client-input:focus {
    border-color: #138496;
    box-shadow: 0 0 0 0.25rem rgba(23,162,184,0.25);
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
                    echo ($estado == 1) ? "Mostrar Ventas Completas" : "Papelera Ventas Completas";
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
            <div class="progress-bar" id="progress-bar" style="width: 33.33%"></div>
        </div>

        <form id="form-alta" method="POST" onsubmit="enviarFormulario(event)">
            
            <!-- PASO 1: Datos de la Salida -->
            <div class="step-card active" id="step-1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Datos de la Salida</h3>
                    <p class="text-muted">Información del producto y envío</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Lote:</label>
                            <select name="fk_lote" class="form-control" required onchange="animateFieldCompletion(this)">
                                <option value="">Seleccionar lote...</option>
                                <?php
                                // Cargar lotes activos
                                $venta = new ControladorVenta();
                                $venta->cargarLotesControlador();
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Cliente:</label>
                            <input type="text" name="cliente" class="form-control client-input" 
                                   placeholder="Nombre del cliente" required 
                                   onchange="animateFieldCompletion(this)" />
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tipo de Salida:</label>
                            <input type="text" name="tipo_salida" class="form-control" 
                                   placeholder="Tipo de Salida" 
                                   onchange="animateFieldCompletion(this)" />
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Destino:</label>
                            <textarea name="destino" class="form-control" rows="2" 
                                      placeholder="Destino de la salida"
                                      onchange="animateFieldCompletion(this)"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Transporte:</label>
                            <input type="text" name="transporte" class="form-control" 
                                   placeholder="Medio de transporte" 
                                   onchange="animateFieldCompletion(this)" />
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Observaciones de Salida:</label>
                            <textarea name="observaciones_salida" class="form-control" rows="2" 
                                      placeholder="Observaciones de la salida"
                                      onchange="animateFieldCompletion(this)"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-danger" onclick="ocultarFormulario()">Salir</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                </div>
            </div>

            <!-- PASO 2: Datos de la Venta -->
            <div class="step-card" id="step-2">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Datos de la Venta</h3>
                    <p class="text-muted">Información financiera y comercial</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ingreso Total:</label>
                    <div class="currency-input">
                        <input type="number" name="ingreso_total" class="form-control ingreso-display" 
                               placeholder="Ingreso Total" step="0.01" min="0" required 
                               onchange="animateFieldCompletion(this); formatCurrency(this)" />
                    </div>
                    <small class="form-text text-muted">Ingrese el monto total de la venta en pesos mexicanos</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Observaciones de Venta:</label>
                    <textarea name="observaciones_venta" class="form-control" rows="4" 
                              placeholder="Observaciones de la venta"
                              onchange="animateFieldCompletion(this)"></textarea>
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Revisar</button>
                    </div>
                </div>
            </div>

            <!-- PASO 3: Confirmación -->
            <div class="step-card" id="step-3">
                <div class="step-header">
                    <div class="step-number">✓</div>
                    <h3 class="step-title">Confirmar Información</h3>
                    <p class="text-muted">Revise los datos antes de guardar la venta</p>
                </div>
                
                <div id="summary-content">
                    <!-- El resumen se llenará dinámicamente -->
                </div>
                
                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-danger me-2" onclick="ocultarFormulario()">Salir</button>
                        <button type="submit" class="btn btn-success">Guardar Venta</button>
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
            <th>Cliente</th>
            <th>Lote</th>
            <th>Destino</th>
            <th>Tipo Salida</th>
            <th>Transporte</th>
            <th>Ingreso Total</th>
            <th>Fecha</th>
            <th>
                <?php if ($estado == 1): ?>
                <button class="btn btn-success btn-sm" onclick="mostrarFormulario(this)" data-title="Alta venta completa">
                    <i class="fa-solid fa-circle-plus fa-lg"></i> Crear Venta
                </button>
                <?php endif; ?>
                
                <!-- Botón para alternar entre activos e inactivos -->
                <form action="index.php" method="POST" style="display: inline;">
                    <input type="hidden" name="opcion" value="mostrar_venta">
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
            $venta = new ControladorVenta();
            $venta->mostrarVentasCompletasControlador($estado);
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

<!-- Scripts para navegación y validaciones -->
<script>
let currentStep = 1;
const totalSteps = 3;

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
    if (step === 3) {
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
    
    // Validación especial para ingreso en step 2
    if (currentStep === 2) {
        const ingreso = parseFloat(document.getElementsByName('ingreso_total')[0].value);
        if (isNaN(ingreso) || ingreso <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error en el ingreso',
                text: 'El ingreso total debe ser mayor a cero'
            });
            return false;
        }
    }
    
    return true;
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

function formatCurrency(input) {
    const value = parseFloat(input.value);
    if (!isNaN(value)) {
        // Formato con decimales
        input.value = value.toFixed(2);
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
    
    const ingreso = parseFloat(formData.get('ingreso_total')) || 0;
    
    const summaryContent = document.getElementById('summary-content');
    summaryContent.innerHTML = `
        <div class="summary-item" style="animation-delay: 0.1s">
            <span>📦 Lote:</span>
            <span>${form.fk_lote.selectedOptions[0]?.text || 'No seleccionado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.2s">
            <span>👤 Cliente:</span>
            <span>${formData.get('cliente') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.3s">
            <span>🏷️ Tipo de Salida:</span>
            <span>${formData.get('tipo_salida') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.4s">
            <span>📍 Destino:</span>
            <span>${formData.get('destino') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.5s">
            <span>🚛 Transporte:</span>
            <span>${formData.get('transporte') || 'No especificado'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.6s">
            <span>📝 Observaciones Salida:</span>
            <span>${formData.get('observaciones_salida') || 'Sin observaciones'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.7s">
            <span>💬 Observaciones Venta:</span>
            <span>${formData.get('observaciones_venta') || 'Sin observaciones'}</span>
        </div>
        <div class="summary-item" style="animation-delay: 0.8s">
            <span>💰 INGRESO TOTAL:</span>
            <span>$${ingreso.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
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
    currentStep = 1;
}

// Validación final del formulario
document.getElementById('form-alta').addEventListener('submit', function(e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
        return false;
    }
    
    const lote = document.getElementsByName('fk_lote')[0].value;
    const cliente = document.getElementsByName('cliente')[0].value;
    const ingreso = parseFloat(document.getElementsByName('ingreso_total')[0].value);
    
    if (!lote) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe seleccionar un lote'
        });
        return false;
    }
    
    if (!cliente.trim()) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre del cliente es requerido'
        });
        return false;
    }
    
    if (isNaN(ingreso) || ingreso <= 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El ingreso total debe ser mayor a cero'
        });
        return false;
    }
});
</script>

<?php
// Manejo del formulario - CÓDIGO ORIGINAL SIN MODIFICACIONES
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el formulario tiene los datos necesarios para venta completa
    if (isset($_POST['fk_lote']) && !empty($_POST['cliente']) && !empty($_POST['ingreso_total'])) {
        
        // Recoger datos del formulario de salida
        $fk_lote = htmlspecialchars($_POST['fk_lote']);
        $tipo_salida = htmlspecialchars($_POST['tipo_salida']);
        $cliente = htmlspecialchars($_POST['cliente']);
        $destino = htmlspecialchars($_POST['destino']);
        $transporte = htmlspecialchars($_POST['transporte']);
        $observaciones_salida = htmlspecialchars($_POST['observaciones_salida']);
        
        // Recoger datos del formulario de venta
        $ingreso_total = htmlspecialchars($_POST['ingreso_total']);
        $observaciones_venta = htmlspecialchars($_POST['observaciones_venta']);

        // Llamar al controlador para manejar el registro completo
        $registro = new ControladorVenta();
        $registro->registroVentaCompletaControlador($fk_lote, $tipo_salida, $cliente, $destino, $transporte, $observaciones_salida, $ingreso_total, $observaciones_venta);
    }
}
?>