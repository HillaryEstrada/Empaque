<div class="reports-container">
    <!-- Encabezado principal -->
    <div class="reports-header text-center mb-5">
        <div class="header-decoration">
            <i class="fas fa-file-pdf report-icon"></i>
        </div>
        <h1 class="reports-title">
            <i class="fas fa-chart-bar me-3"></i>
            Centro de Reportes
        </h1>
        <p class="reports-subtitle">Genere y descargue informes detallados del sistema</p>
        <div class="header-divider"></div>
    </div>

    <!-- Grid de tarjetas de reportes -->
    <div class="reports-grid row g-4">
        
        <!-- Reporte de Roles -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card">
                <div class="card-header-custom">
                    <div class="report-card-icon roles-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="card-title">Catálogo de Roles</h3>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Reporte completo de todos los roles del sistema con sus descripciones y fechas de creación.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-calendar-alt"></i>
                            Actualizado diariamente
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <a href="vistas/modulos/fpdf/reporte_roles.php" target="_blank" class="btn-report btn-roles">
                        <i class="fas fa-download me-2"></i>
                        Generar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Usuarios -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card">
                <div class="card-header-custom">
                    <div class="report-card-icon users-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Datos de Usuarios</h3>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Información completa de todos los usuarios registrados en el sistema.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-user-check"></i>
                            Incluye activos e inactivos
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <button class="btn-report btn-users" onclick="generateUserReport()">
                        <i class="fas fa-download me-2"></i>
                        Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Reporte de Productores -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card">
                <div class="card-header-custom">
                    <div class="report-card-icon producers-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3 class="card-title">Directorio de Productores</h3>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Lista completa de productores con información de contacto y detalles relevantes.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-phone"></i>
                            Incluye datos de contacto
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <button class="btn-report btn-producers" onclick="generateProducerReport()">
                        <i class="fas fa-download me-2"></i>
                        Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Reporte General -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card featured">
                <div class="card-header-custom">
                    <div class="report-card-icon general-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="card-title">Reporte General</h3>
                    <span class="featured-badge">Destacado</span>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Resumen ejecutivo con estadísticas generales y métricas del sistema completo.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-chart-line"></i>
                            Análisis completo
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <button class="btn-report btn-general" onclick="generateGeneralReport()">
                        <i class="fas fa-download me-2"></i>
                        Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Reporte Personalizado -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card">
                <div class="card-header-custom">
                    <div class="report-card-icon custom-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="card-title">Reporte Personalizado</h3>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Configure y genere reportes personalizados según sus necesidades específicas.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-filter"></i>
                            Filtros avanzados
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <button class="btn-report btn-custom" onclick="openCustomReportModal()">
                        <i class="fas fa-wrench me-2"></i>
                        Configurar
                    </button>
                </div>
            </div>
        </div>

        <!-- Historial de Reportes -->
        <div class="col-md-6 col-lg-4">
            <div class="report-card">
                <div class="card-header-custom">
                    <div class="report-card-icon history-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="card-title">Historial de Reportes</h3>
                </div>
                <div class="card-body-custom">
                    <p class="card-description">
                        Acceda al historial de reportes generados anteriormente y descárguelos nuevamente.
                    </p>
                    <div class="card-stats">
                        <span class="stat-item">
                            <i class="fas fa-archive"></i>
                            Últimos 30 días
                        </span>
                    </div>
                </div>
                <div class="card-footer-custom">
                    <button class="btn-report btn-history" onclick="showReportHistory()">
                        <i class="fas fa-eye me-2"></i>
                        Ver Historial
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Sección de estadísticas rápidas -->
    <div class="quick-stats-section mt-5">
        <h3 class="section-title">
            <i class="fas fa-tachometer-alt me-2"></i>
            Estadísticas Rápidas
        </h3>
        <div class="stats-row row g-3">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h4 id="total-users">--</h4>
                        <p>Total Usuarios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-content">
                        <h4 id="total-roles">--</h4>
                        <p>Total Roles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="stat-content">
                        <h4 id="total-producers">--</h4>
                        <p>Total Productores</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-content">
                        <h4 id="total-reports">--</h4>
                        <p>Reportes Generados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el centro de reportes */
.reports-container {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.reports-header {
    position: relative;
    margin-bottom: 3rem;
}

.header-decoration {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0.1;
}

.report-icon {
    font-size: 8rem;
    color: #6f42c1;
}

.reports-title {
    color: #2c3e50;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #6f42c1, #e83e8c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.reports-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
}

.header-divider {
    width: 100px;
    height: 3px;
    background: linear-gradient(45deg, #6f42c1, #e83e8c);
    margin: 0 auto;
    border-radius: 2px;
}

.reports-grid {
    max-width: 1200px;
    margin: 0 auto;
}

.report-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    position: relative;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.report-card.featured {
    border: 2px solid #6f42c1;
    background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: -25px;
    background: linear-gradient(45deg, #6f42c1, #e83e8c);
    color: white;
    padding: 5px 35px;
    font-size: 0.8rem;
    transform: rotate(45deg);
    font-weight: bold;
}

.card-header-custom {
    text-align: center;
    padding: 1.5rem;
    position: relative;
}

.report-card-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.roles-icon { background: linear-gradient(45deg, #667eea, #764ba2); }
.users-icon { background: linear-gradient(45deg, #f093fb, #f5576c); }
.producers-icon { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.general-icon { background: linear-gradient(45deg, #43e97b, #38f9d7); }
.custom-icon { background: linear-gradient(45deg, #fa709a, #fee140); }
.history-icon { background: linear-gradient(45deg, #a8edea, #fed6e3); }

.card-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.card-body-custom {
    padding: 0 1.5rem 1rem;
}

.card-description {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.card-stats {
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-item {
    color: #6f42c1;
    font-size: 0.85rem;
    font-weight: 500;
}

.stat-item i {
    margin-right: 0.5rem;
}

.card-footer-custom {
    padding: 1rem 1.5rem 1.5rem;
}

.btn-report {
    width: 100%;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    color: white;
}

.btn-roles { background: linear-gradient(45deg, #667eea, #764ba2); }
.btn-users { background: linear-gradient(45deg, #f093fb, #f5576c); }
.btn-producers { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.btn-general { background: linear-gradient(45deg, #43e97b, #38f9d7); }
.btn-custom { background: linear-gradient(45deg, #fa709a, #fee140); }
.btn-history { background: linear-gradient(45deg, #a8edea, #fed6e3); color: #333; }

.btn-report:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    color: white;
}

.quick-stats-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.section-title {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(45deg, #6f42c1, #e83e8c);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-content h4 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.stat-content p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .reports-container {
        padding: 1rem;
    }
    
    .reports-title {
        font-size: 2rem;
    }
    
    .report-card-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<script>
// Funciones para los botones de reportes
function generateUserReport() {
    Swal.fire({
        title: 'Generando Reporte de Usuarios',
        text: 'Por favor espere...',
        icon: 'info',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        // Aquí puedes agregar la lógica para generar el reporte de usuarios
        window.open('vistas/modulos/fpdf/reporte_usuarios.php', '_blank');
    });
}

function generateProducerReport() {
    Swal.fire({
        title: 'Generando Reporte de Productores',
        text: 'Por favor espere...',
        icon: 'info',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        // Aquí puedes agregar la lógica para generar el reporte de productores
        window.open('vistas/modulos/fpdf/reporte_productores.php', '_blank');
    });
}

function generateGeneralReport() {
    Swal.fire({
        title: 'Generando Reporte General',
        text: 'Por favor espere...',
        icon: 'info',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        // Aquí puedes agregar la lógica para generar el reporte general
        window.open('vistas/modulos/fpdf/reporte_general.php', '_blank');
    });
}

function openCustomReportModal() {
    Swal.fire({
        title: 'Reporte Personalizado',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Seleccione el tipo de reporte:</label>
                    <select class="form-select" id="reportType">
                        <option value="usuarios">Usuarios</option>
                        <option value="roles">Roles</option>
                        <option value="productores">Productores</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Período:</label>
                    <select class="form-select" id="reportPeriod">
                        <option value="all">Todos los registros</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta semana</option>
                        <option value="month">Este mes</option>
                        <option value="year">Este año</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <select class="form-select" id="reportStatus">
                        <option value="all">Todos</option>
                        <option value="active">Solo activos</option>
                        <option value="inactive">Solo inactivos</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generar Reporte',
        cancelButtonText: 'Cancelar',
        width: '500px'
    }).then((result) => {
        if (result.isConfirmed) {
            const type = document.getElementById('reportType').value;
            const period = document.getElementById('reportPeriod').value;
            const status = document.getElementById('reportStatus').value;
            
            Swal.fire('¡Reporte Generado!', 'Su reporte personalizado ha sido creado.', 'success');
        }
    });
}

function showReportHistory() {
    Swal.fire({
        title: 'Historial de Reportes',
        html: `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-08-10</td>
                            <td>Catálogo de Roles</td>
                            <td><button class="btn btn-sm btn-primary">Descargar</button></td>
                        </tr>
                        <tr>
                            <td>2025-08-09</td>
                            <td>Datos de Usuarios</td>
                            <td><button class="btn btn-sm btn-primary">Descargar</button></td>
                        </tr>
                        <tr>
                            <td>2025-08-08</td>
                            <td>Reporte General</td>
                            <td><button class="btn btn-sm btn-primary">Descargar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `,
        width: '600px',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Cerrar'
    });
}

// Cargar estadísticas al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Simulación de carga de estadísticas
    setTimeout(() => {
        document.getElementById('total-users').textContent = '25';
        document.getElementById('total-roles').textContent = '8';
        document.getElementById('total-producers').textContent = '15';
        document.getElementById('total-reports').textContent = '42';
    }, 500);
});
</script>