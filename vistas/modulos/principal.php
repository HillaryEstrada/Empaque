<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Mango - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            width: 100%;
            min-height: 100vh;
            padding: 16px;
            max-width: 100vw;
            overflow-x: hidden;
        }
        /* Header */
        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .header-left img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e5e7eb;
            object-fit: cover;
        }
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
        }
        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .header-title span {
            color: #64748b;
            font-size: 16px;
        }
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn-icon {
            background: #f8fafc;
            border: none;
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-icon.notification {
            background: linear-gradient(135deg, #fef3c7, #fbbf24);
        }
        .btn-icon.notification:hover {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }
        .btn-icon.notification i {
            color: #92400e;
            font-size: 18px;
            animation: bell-ring 2s infinite;
        }
        .btn-icon.settings {
            background: linear-gradient(135deg, #e0e7ff, #6366f1);
        }
        .btn-icon.settings:hover {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }
        .btn-icon.settings i {
            color: #3730a3;
            font-size: 18px;
            animation: rotate-gear 3s linear infinite;
        }
        .btn-icon.settings:hover i {
            color: white;
        }
        .btn-icon.notification:hover i {
            color: #7c2d12;
        }
        @keyframes bell-ring {
            0%, 50%, 100% { 
                transform: rotate(0deg); 
            }
            10%, 30% { 
                transform: rotate(10deg); 
            }
            20% { 
                transform: rotate(-10deg); 
            }
        }
        @keyframes rotate-gear {
            0% { 
                transform: rotate(0deg); 
            }
            100% { 
                transform: rotate(360deg); 
            }
        }
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .stat-card.empaque::before { background: #3b82f6; }
        .stat-card.productores::before { background: #10b981; }
        .stat-card.reportes::before { background: #f59e0b; }
        .stat-card.usuarios::before { background: #8b5cf6; }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        .stat-icon.empaque { background: #3b82f6; }
        .stat-icon.productores { background: #10b981; }
        .stat-icon.reportes { background: #f59e0b; }
        .stat-icon.usuarios { background: #8b5cf6; }
        .stat-title {
            font-size: 16px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        .stat-change {
            font-size: 14px;
            margin-top: 8px;
        }
        .stat-change.positive {
            color: #10b981;
        }
        .stat-change.negative {
            color: #ef4444;
        }
        /* Main Content Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }
        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .action-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            width: 100%;
        }
        .action-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }
        .action-icon.productores { background: #10b981; }
        .action-icon.pdf { background: #f59e0b; }
        .action-icon.usuarios { background: #8b5cf6; }
        .action-icon.inventario { background: #3b82f6; }
        .action-text {
            font-size: 16px;
            font-weight: 500;
            color: #374151;
        }
        /* Chart Section */
        .chart-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            height: fit-content;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            overflow: hidden;
        }
        .chart-container canvas {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
        }
        .chart-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 20px;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 4px;
        }
        .chart-tab {
            flex: 1;
            background: transparent;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }
        .chart-tab.active {
            background: white;
            color: #1e293b;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        /* Recent Activity */
        .recent-activity {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
        }
        .activity-icon.success { background: #10b981; }
        .activity-icon.warning { background: #f59e0b; }
        .activity-icon.info { background: #3b82f6; }
        .activity-content {
            flex: 1;
        }
        .activity-title {
            font-size: 16px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .activity-time {
            font-size: 14px;
            color: #64748b;
        }
        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr 350px;
                gap: 16px;
            }
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }
        @media (max-width: 1024px) {
            .dashboard-container {
                padding: 12px;
            }
            .main-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            }
        }
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 8px;
            }
            .dashboard-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
                padding: 16px;
            }
            .header-left {
                gap: 12px;
            }
            .header-title h1 {
                font-size: 24px;
            }
            .header-title span {
                font-size: 15px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .actions-grid {
                grid-template-columns: 1fr;
            }
            .stat-card {
                padding: 16px;
            }
            .chart-section, .quick-actions, .recent-activity {
                padding: 16px;
            }
            .chart-container {
                height: 300px;
            }
            .chart-tab {
                padding: 10px 16px;
                font-size: 15px;
            }
        }
        @media (max-width: 480px) {
            .dashboard-container {
                padding: 4px;
            }
            .dashboard-header {
                padding: 12px;
                margin-bottom: 12px;
            }
            .stats-grid {
                margin-bottom: 12px;
                gap: 8px;
            }
            .main-grid {
                gap: 12px;
                margin-bottom: 12px;
            }
            .stat-card {
                padding: 12px;
            }
            .stat-value {
                font-size: 28px;
            }
            .stat-title {
                font-size: 15px;
            }
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .btn-icon {
                width: 40px;
                height: 40px;
            }
            .chart-container {
                height: 250px;
            }
            .chart-tab {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-left">
                <div class="user-avatar">
                    <?php 
                    $nombre = $_SESSION["nombre"] ?? $_SESSION["usuario"] ?? "U";
                    echo strtoupper(substr($nombre, 0, 1)); 
                    ?>
                </div>
                <div class="header-title">
                    <h1>Panel de Control - Empaque</h1>
                    <span>Bienvenido, <?php echo $_SESSION["nombre"] ?? $_SESSION["usuario"] ?? "Usuario"; ?> | <?php echo $_SESSION["rol"] ?? "Empleado"; ?></span>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-icon notification" title="Notificaciones">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="btn-icon settings" title="Configuración">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card empaque">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Cajas Empacadas Hoy</div>
                        <div class="stat-value">120</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +12% desde ayer
                        </div>
                    </div>
                    <div class="stat-icon empaque">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card productores">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Productores Activos</div>
                        <div class="stat-value">8</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +2 este mes
                        </div>
                    </div>
                    <div class="stat-icon productores">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card reportes">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Reportes Generados</div>
                        <div class="stat-value">5</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +1 esta semana
                        </div>
                    </div>
                    <div class="stat-icon reportes">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card usuarios">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Usuarios Registrados</div>
                        <div class="stat-value">15</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +3 este mes
                        </div>
                    </div>
                    <div class="stat-icon usuarios">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Chart Section -->
            <div class="chart-section">
                <div class="section-title">Análisis de Producción</div>
                <div class="chart-tabs">
                    <button class="chart-tab active">Semanal</button>
                    <button class="chart-tab">Mensual</button>
                    <button class="chart-tab">Anual</button>
                </div>
                <div class="chart-container">
                    <canvas id="chartProduccion"></canvas>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div>
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-title">Acciones Rápidas</div>
                    <div class="actions-grid">
                        <form action="../../index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="opcion" value="mostrar_productor">
                            <button type="submit" class="action-btn">
                                <div class="action-icon productores">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="action-text">Gestionar Productores</div>
                            </button>
                        </form>
                        
                        <form action="../../index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="opcion" value="pdf">
                            <button type="submit" class="action-btn">
                                <div class="action-icon pdf">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="action-text">Generar Reportes</div>
                            </button>
                        </form>
                        
                        <form action="../../index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="opcion" value="mostrar_dato_usuario">
                            <button type="submit" class="action-btn">
                                <div class="action-icon usuarios">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="action-text">Administrar Usuarios</div>
                            </button>
                        </form>
                        
                        <form action="../../index.php" method="POST" style="display:inline;">
                            <input type="hidden" name="opcion" value="inventario">
                            <button type="submit" class="action-btn">
                                <div class="action-icon inventario">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="action-text">Control Inventario</div>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <div class="section-title">Actividad Reciente</div>
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nuevo productor registrado</div>
                            <div class="activity-time">Hace 2 horas</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">85 cajas empacadas</div>
                            <div class="activity-time">Hace 4 horas</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Reporte mensual generado</div>
                            <div class="activity-time">Ayer</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Usuario actualizado</div>
                            <div class="activity-time">Hace 2 días</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart.js para los gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Variable global para el gráfico
        let chartProduccion;

        // Función para crear/recrear el gráfico
        function createChart() {
            const ctx = document.getElementById('chartProduccion');
            if (!ctx) return;

            // Destruir gráfico existente si existe
            if (chartProduccion) {
                chartProduccion.destroy();
            }

            // Crear nuevo gráfico
            chartProduccion = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Cajas Empacadas',
                        data: [65, 78, 90, 81, 85, 95, 120],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Productores Activos',
                        data: [6, 7, 8, 7, 8, 8, 8],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    resizeDelay: 0,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 300
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 16
                                },
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            titleFont: {
                                size: 16
                            },
                            bodyFont: {
                                size: 14
                            },
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 14
                                },
                                padding: 8
                            }
                        },
                        x: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 14
                                },
                                padding: 8
                            }
                        }
                    }
                }
            });
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            createChart();
        });

        // Funcionalidad de las pestañas del gráfico
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.chart-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remover clase active de todas las pestañas
                    document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
                    // Agregar clase active a la pestaña clickeada
                    this.classList.add('active');
                    
                    if (!chartProduccion) return;
                    
                    // Actualizar datos según la pestaña seleccionada
                    const tabText = this.textContent;
                    let newData, newLabels;
                    
                    if (tabText === 'Semanal') {
                        newLabels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                        newData = [65, 78, 90, 81, 85, 95, 120];
                    } else if (tabText === 'Mensual') {
                        newLabels = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'];
                        newData = [280, 320, 350, 400];
                    } else if (tabText === 'Anual') {
                        newLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        newData = [1200, 1400, 1300, 1600, 1800, 1900, 2100, 2000, 1800, 1900, 2200, 2400];
                    }
                    
                    chartProduccion.data.labels = newLabels;
                    chartProduccion.data.datasets[0].data = newData;
                    chartProduccion.update();
                });
            });
        });

        // Redimensionamiento optimizado
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (chartProduccion) {
                    chartProduccion.resize();
                }
            }, 150);
        });

        // Observer para cambios en el contenedor
        if (window.ResizeObserver) {
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                const resizeObserver = new ResizeObserver(function(entries) {
                    if (chartProduccion) {
                        clearTimeout(resizeTimeout);
                        resizeTimeout = setTimeout(function() {
                            chartProduccion.resize();
                        }, 100);
                    }
                });
                resizeObserver.observe(chartContainer);
            }
        }

        // Forzar redimensionamiento cuando la página esté completamente cargada
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (chartProduccion) {
                    chartProduccion.resize();
                }
            }, 300);
        });
    </script>
</body>
</html>