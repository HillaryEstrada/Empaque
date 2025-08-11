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
    <title>Dashboard Empleado - Empacadora Mango</title>
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
            background: #ffffff;
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
        
        /* Header Empleado */
        .dashboard-header {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .user-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
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
            font-weight: 500;
        }
        .badge-empleado {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn-icon {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .btn-icon.notification {
            background: linear-gradient(135deg, #fef3c7, #fbbf24);
        }
        .btn-icon.notification i {
            color: #92400e;
            font-size: 18px;
            animation: bell-ring 2s infinite;
        }
        .btn-icon.profile {
            background: linear-gradient(135deg, #ddd6fe, #8b5cf6);
        }
        .btn-icon.profile i {
            color: #5b21b6;
            font-size: 18px;
        }
        @keyframes bell-ring {
            0%, 50%, 100% { transform: rotate(0deg); }
            10%, 30% { transform: rotate(10deg); }
            20% { transform: rotate(-10deg); }
        }
        
        /* Stats Cards Empleado */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .stat-card.cajas::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
        .stat-card.productores::before { background: linear-gradient(90deg, #10b981, #059669); }
        .stat-card.calidad::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .stat-card.tiempo::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .stat-icon.cajas { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.productores { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.calidad { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.tiempo { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
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
            font-weight: 500;
        }
        .stat-change.positive { color: #10b981; }
        .stat-change.neutral { color: #64748b; }
        
        /* Main Content Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
            align-items: start;
        }
        
        /* Tasks Section */
        .tasks-section {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i {
            color: #3b82f6;
        }
        .task-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            background: white;
        }
        .task-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
            transform: translateX(2px);
        }
        .task-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .task-checkbox.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            border-color: #10b981;
            color: white;
        }
        .task-content {
            flex: 1;
        }
        .task-title {
            font-size: 16px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .task-time {
            font-size: 14px;
            color: #64748b;
        }
        .task-priority {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .task-priority.alta { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .task-priority.media { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .task-priority.baja { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
        
        /* Quick Actions Empleado */
        .quick-actions {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .action-btn {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            width: 100%;
        }
        .action-btn:hover {
            background: #f8fafc;
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .action-icon.productores { background: linear-gradient(135deg, #10b981, #059669); }
        .action-icon.reportes { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .action-icon.inventario { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .action-text {
            font-size: 16px;
            font-weight: 500;
            color: #374151;
        }
        
        /* Chart Section */
        .chart-section {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            grid-column: 1 / -1;
        }
        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
            margin-top: 20px;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 12px;
            }
            .dashboard-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
                padding: 20px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .main-grid {
                gap: 16px;
            }
        }
        @media (max-width: 480px) {
            .dashboard-container {
                padding: 8px;
            }
            .stat-card, .tasks-section, .quick-actions, .chart-section {
                padding: 16px;
            }
            .header-title h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header Empleado -->
        <div class="dashboard-header">
            <div class="header-left">
                <div class="user-avatar">
                    <?php 
                    $nombre = $_SESSION["nombre"] ?? $_SESSION["usuario"] ?? "E";
                    echo strtoupper(substr($nombre, 0, 1)); 
                    ?>
                </div>
                <div class="header-title">
                    <h1>Panel de Empleado - Empaque Mango</h1>
                    <span>Bienvenido, <?php echo $_SESSION["nombre"] ?? $_SESSION["usuario"] ?? "Empleado"; ?></span>
                    <span class="badge-empleado">EMPLEADO</span>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-icon notification" title="Notificaciones">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="btn-icon profile" title="Mi Perfil">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards Empleado -->
        <div class="stats-grid">
            <div class="stat-card cajas">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Cajas Empacadas Hoy</div>
                        <div class="stat-value">42</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> Meta: 50 cajas
                        </div>
                    </div>
                    <div class="stat-icon cajas">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card productores">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Productores Asignados</div>
                        <div class="stat-value">3</div>
                        <div class="stat-change neutral">
                            <i class="fas fa-users"></i> Trabajando hoy
                        </div>
                    </div>
                    <div class="stat-icon productores">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card calidad">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Índice de Calidad</div>
                        <div class="stat-value">94%</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +2% esta semana
                        </div>
                    </div>
                    <div class="stat-icon calidad">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card tiempo">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Tiempo Promedio</div>
                        <div class="stat-value">1.2m</div>
                        <div class="stat-change positive">
                            <i class="fas fa-clock"></i> Por caja
                        </div>
                    </div>
                    <div class="stat-icon tiempo">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Tasks Section -->
            <div class="tasks-section">
                <div class="section-title">
                    <i class="fas fa-tasks"></i>
                    Mis Tareas del Día
                </div>
                
                <div class="task-item">
                    <div class="task-checkbox completed">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="task-content">
                        <div class="task-title">Revisar calidad del lote #001</div>
                        <div class="task-time">Completado - 8:30 AM</div>
                    </div>
                    <div class="task-priority alta">Alta</div>
                </div>
                
                <div class="task-item">
                    <div class="task-checkbox">
                    </div>
                    <div class="task-content">
                        <div class="task-title">Empacar producción de José Martínez</div>
                        <div class="task-time">En progreso - 10:15 AM</div>
                    </div>
                    <div class="task-priority media">Media</div>
                </div>
                
                <div class="task-item">
                    <div class="task-checkbox">
                    </div>
                    <div class="task-content">
                        <div class="task-title">Actualizar inventario de cajas</div>
                        <div class="task-time">Pendiente - 2:00 PM</div>
                    </div>
                    <div class="task-priority baja">Baja</div>
                </div>
                
                <div class="task-item">
                    <div class="task-checkbox">
                    </div>
                    <div class="task-content">
                        <div class="task-title">Preparar reporte semanal</div>
                        <div class="task-time">Pendiente - 4:00 PM</div>
                    </div>
                    <div class="task-priority media">Media</div>
                </div>
            </div>

            <!-- Quick Actions Empleado -->
            <div class="quick-actions">
                <div class="section-title">
                    <i class="fas fa-bolt"></i>
                    Acciones Rápidas
                </div>
                <div class="actions-grid">
                    <form action="../../index.php" method="POST" style="display:inline;">
                        <input type="hidden" name="opcion" value="mostrar_productor">
                        <button type="submit" class="action-btn">
                            <div class="action-icon productores">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="action-text">Ver Productores</div>
                        </button>
                    </form>
                    
                    <button class="action-btn" onclick="verInventario()">
                        <div class="action-icon inventario">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="action-text">Inventario</div>
                    </button>
                    
                    <button class="action-btn" onclick="verMisReportes()">
                        <div class="action-icon reportes">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="action-text">Mis Reportes</div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <div class="section-title">
                <i class="fas fa-chart-area"></i>
                Mi Rendimiento Semanal
            </div>
            <div class="chart-container">
                <canvas id="chartRendimiento"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de rendimiento del empleado
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartRendimiento');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Cajas Empacadas',
                        data: [45, 52, 48, 61, 55, 67, 42],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Meta Diaria',
                        data: [50, 50, 50, 50, 50, 50, 50],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        pointRadius: 3
                    }, {
                        label: 'Calidad (%)',
                        data: [92, 95, 91, 96, 93, 98, 94],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 14 },
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            titleFont: { size: 16 },
                            bodyFont: { size: 14 },
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            title: {
                                display: true,
                                text: 'Cajas',
                                font: { size: 14 }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            min: 80,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Calidad (%)',
                                font: { size: 14 }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                        x: {
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        });

        // Funcionalidad de tareas
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    if (!this.classList.contains('completed')) {
                        this.classList.toggle('completed');
                        if (this.classList.contains('completed')) {
                            this.innerHTML = '<i class="fas fa-check"></i>';
                            // Actualizar texto de tiempo
                            const taskItem = this.closest('.task-item');
                            const timeSpan = taskItem.querySelector('.task-time');
                            timeSpan.textContent = 'Completado - ' + new Date().toLocaleTimeString();
                        }
                    }
                });
            });
        });

        // Funciones de acciones rápidas
        function verInventario() {
            alert('Función de Inventario - Próximamente disponible');
        }

        function verMisReportes() {
            alert('Función de Mis Reportes - Próximamente disponible');
        }
    </script>
</body>
</html>
