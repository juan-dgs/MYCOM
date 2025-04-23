<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/gantt.js"></script>


<style>
    .timeline {
        position: absolute;
        padding-left: 50px;
        top: 0;
        left: -20px;
    }

    /* .timeline::before {
        content: '';
        position: absolute;
        left: 25px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }*/

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    /* .timeline-item::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 15px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background-color: #0d6efd;
        border: 1px solid white;
        z-index: 10;
    }*/
    .timeline-item .date {
        position: absolute;
        top: 0;
        width: 100%;
        right: 14px;
        text-align: right;
    }

    .timeline-item .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: absolute;
        left: -30px;
    }

    .timeline-item .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .timeline-item .card:hover {
        transform: translateY(-5px);
    }

    .timeline-item .card-title {
        font-size: 18px;
        font-weight: bold;
    }



    /* ESTILOS CARDS EFICIENCIA POR PERSONA */

    .employee-card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        overflow: hidden;
        position: relative;
        margin-bottom: 30px;
    }

    .employee-card:hover {
        transform: translateY(-5px);
    }

    .employee-card .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }

    .employee-card .avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
    }

    .employee-card .pie-chart {
        width: 120px;
        height: 120px;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .employee-card .counter {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .employee-card .counter-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .employee-card .star-rating {
        color: #ffc107;
        font-size: 1.2rem;
    }

    .employee-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    /* Gráfico Gantt */
    #ganttChart {
            height: 600px;
            min-width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .gantt-header {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }
        .avatar-gantt {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .custom-tooltip {
            min-width: 300px;
            font-family: Arial, sans-serif;
        }
        .tooltip-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .tooltip-section {
            margin-bottom: 8px;
        }
        .tooltip-label {
            font-weight: bold;
            color: #7f8c8d;
            display: inline-block;
            width: 100px;
        }
        .tooltip-value {
            color: #34495e;
        }
        .tooltip-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
            color: white;
        }
        .progress-comparison {
            width: 100%;
            margin-top: 5px;
            position: relative;
            height: 20px;
        }
        .progress-bar-plan {
            height: 10px;
            background-color: rgba(52, 152, 219, 0.3);
            border-radius: 4px;
            position: absolute;
            top: 0;
            left: 0;
        }
        .progress-bar-real {
            height: 6px;
            background-color: #2ecc71;
            border-radius: 4px;
            position: absolute;
            top: 12px;
            left: 0;
        }
        .highcharts-point-plan {
            stroke-width: 1px;
            stroke: rgba(0,0,0,0.2);
            borderRadius: 2px;
            pointPadding: 0.1;
            groupPadding: 0.2;
        }
        .highcharts-point-real {
            stroke-width: 1px;
            stroke: rgba(0,0,0,0.2);
            border-Radius: 2px;
            pointWidth: 6;
        }
        .highcharts-series-1 .highcharts-point {
            pointWidth: 6;
        }
        .legend-item {
            display: inline-block;
            margin-right: 15px;
            font-size: 0.9em;
        }
        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
            vertical-align: middle;
        }
</style>
<div class="row">
    <div class="col-xs-12 col-lg-10  col-10">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Monitoreo</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <!-- Empleado 1 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="employee-card card">
                            <div class="card-header text-center">
                                <div class="avatar-container">
                                    <div id="pieChart1" class="pie-chart"></div>
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Avatar" class="avatar">
                                </div>
                                <h4 class="mt-3 mb-0">Ana Martínez</h4>
                                <div class="star-rating mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span class="ms-1">4.0</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="counter">24</div>
                                        <div class="counter-label">Act</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">18</div>
                                        <div class="counter-label">Fin</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">15</div>
                                        <div class="counter-label">SLA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empleado 2 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="employee-card card">
                            <div class="card-header text-center">
                                <div class="avatar-container">
                                    <div id="pieChart2" class="pie-chart"></div>
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar" class="avatar">
                                </div>
                                <h4 class="mt-3 mb-0">Carlos Rodríguez</h4>
                                <div class="star-rating mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="ms-1">5.0</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="counter">32</div>
                                        <div class="counter-label">Act</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">30</div>
                                        <div class="counter-label">Fin</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">28</div>
                                        <div class="counter-label">SLA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empleado 3 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="employee-card card">
                            <div class="card-header text-center">
                                <div class="avatar-container">
                                    <div id="pieChart3" class="pie-chart"></div>
                                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Avatar" class="avatar">
                                </div>
                                <h4 class="mt-3 mb-0">María González</h4>
                                <div class="star-rating mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="ms-1">4.5</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="counter">19</div>
                                        <div class="counter-label">Act</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">14</div>
                                        <div class="counter-label">Fin</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">12</div>
                                        <div class="counter-label">SLA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empleado 4 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="employee-card card">
                            <div class="card-header text-center">
                                <div class="avatar-container">
                                    <div id="pieChart4" class="pie-chart"></div>
                                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Avatar" class="avatar">
                                </div>
                                <h4 class="mt-3 mb-0">Juan Pérez</h4>
                                <div class="star-rating mb-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span class="ms-1">3.0</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="counter">27</div>
                                        <div class="counter-label">Act</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">16</div>
                                        <div class="counter-label">Fin</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="counter">10</div>
                                        <div class="counter-label">SLA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-xs-12 col-lg-2 col-2">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Actividad Reciente</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 row" style="    height: 500px;       overflow-x: hidden; position:relative;">
                <div class="container py-5">
                    <div class="timeline">
                        <!-- Item 1 -->
                        <div class="timeline-item">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Avatar" class="avatar me-3">
                                        <div>
                                            <h5 class="card-title mb-0">Ana Martínez</h5>
                                            <small class="text-muted date"><i class="far fa-calendar-alt me-1"></i> 15 Marzo 2023</small>
                                        </div>
                                    </div>
                                    <h4 class="mb-3 card-title">Lanzamiento del nuevo producto</h4>
                                    <p class="card-text">Hoy hemos lanzado nuestra nueva línea de productos ecológicos. Estamos muy emocionados por compartir esta innovación con todos nuestros clientes.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="timeline-item">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar" class="avatar me-3">
                                        <div>
                                            <h5 class="card-title mb-0">Carlos Rodríguez</h5>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> 28 Febrero 2023</small>
                                        </div>
                                    </div>
                                    <h4 class="mb-3">Conferencia de tecnología</h4>
                                    <p class="card-text">Participamos como expositores en la conferencia anual de tecnología. Compartimos nuestras últimas investigaciones en inteligencia artificial.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="timeline-item">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Avatar" class="avatar me-3">
                                        <div>
                                            <h5 class="card-title mb-0">María González</h5>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> 10 Enero 2023</small>
                                        </div>
                                    </div>
                                    <h4 class="mb-3">Premio a la innovación</h4>
                                    <p class="card-text">Recibimos el premio a la empresa más innovadora del año en nuestra categoría. Un reconocimiento al trabajo de todo el equipo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="col-xs-12 col-lg-12 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Gantt</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 row">
                <div id="ganttChart"></div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>



</div>


<script>
    // Configuración común para los gráficos de pastel
    const pieOptions = {
        chart: {
            type: 'pie',
            width: 120,
            height: 120,
            backgroundColor: 'transparent',
            margin: [0, 0, 0, 0],
            spacing: [0, 0, 0, 0]
        },
        title: {
            text: null
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            pie: {
                innerSize: '70%',
                borderWidth: 0,
                dataLabels: {
                    enabled: false
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Actividades',
            colorByPoint: true,
            data: [{
                    name: 'Urgentes',
                    y: 35,
                    color: '#dc3545'
                },
                {
                    name: 'Normales',
                    y: 45,
                    color: '#fd7e14'
                },
                {
                    name: 'Bajas',
                    y: 20,
                    color: '#28a745'
                }
            ]
        }]
    };

    // Inicializar gráficos para cada empleado
    document.addEventListener('DOMContentLoaded', function() {
        Highcharts.chart('pieChart1', pieOptions);
        Highcharts.chart('pieChart2', pieOptions);
        Highcharts.chart('pieChart3', pieOptions);
        Highcharts.chart('pieChart4', pieOptions);

          // Configuración de avatares y empleados
          const avatars = [
                'https://randomuser.me/api/portraits/women/44.jpg', // Ana
                'https://randomuser.me/api/portraits/men/32.jpg',   // Carlos
                'https://randomuser.me/api/portraits/women/68.jpg',  // María
                'https://randomuser.me/api/portraits/men/75.jpg'     // Juan
            ];
            
            const empleados = [
                'Ana Martínez',
                'Carlos Rodríguez',
                'María González',
                'Juan Pérez'
            ];
            
            // Configurar semana actual
            const today = new Date();
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Lunes
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6); // Domingo
            
            // Función para calcular diferencia entre fechas en días
            function daysBetween(start, end) {
                return Math.round((end - start) / (1000 * 60 * 60 * 24));
            }
            
            // Crear gráfico Gantt
            Highcharts.ganttChart('ganttChart', {
                title: { text: null },
                chart: {
                    spacingTop: 30
                },
                xAxis: {
                    currentDateIndicator: true,
                    min: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()),
                    max: Date.UTC(endOfWeek.getFullYear(), endOfWeek.getMonth(), endOfWeek.getDate(), 23, 59, 59),
                    labels: { format: '{value:%e %b}' }
                },
                yAxis: {
                    uniqueNames: true,
                    categories: empleados,
                    labels: {
                        formatter: function() {
                            return `<img src="${avatars[this.pos]}" class="avatar-gantt" title="${this.value}">`;
                        },
                        useHTML: true
                    },
                    grid: { enabled: true, borderWidth: 0 },
                    tickInterval: 1
                },
                tooltip: {
                    useHTML: true,
                    formatter: function() {
                        const point = this.point;
                        const statusColors = {
                            'Completada': '#2ecc71',
                            'En progreso': '#3498db',
                            'Pendiente': '#f39c12',
                            'Atrasada': '#e74c3c',
                            'En riesgo': '#f1c40f'
                        };
                        
                        // Calcular diferencia entre plan y real
                        let diffDays = 0;
                        let diffText = '<span style="color:#2ecc71">En plazo</span>';
                        
                        if (point.realEnd) {
                            diffDays = daysBetween(point.realEnd, point.end);
                            diffText = `<span style="color:${diffDays >= 0 ? '#2ecc71' : '#e74c3c'}">
                                ${Math.abs(diffDays)} día${Math.abs(diffDays) !== 1 ? 's' : ''} 
                                ${diffDays >= 0 ? 'adelantada' : 'atrasada'}
                            </span>`;
                        }
                        
                        // Calcular porcentaje completado
                        const progress = point.realProgress || (point.realStart ? 50 : 0);
                        const progressClass = progress < 30 ? 'text-danger' : 
                                             progress < 70 ? 'text-warning' : 'text-success';
                        
                        return `
                            <div class="custom-tooltip">
                                <div class="tooltip-title">${point.name}</div>
                                
                                <div class="tooltip-section">
                                    <img src="${avatars[point.y]}" class="avatar-gantt me-2">
                                    <span class="tooltip-value">${empleados[point.y]}</span>
                                </div>
                                
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Estado:</span>
                                    <span class="tooltip-status" style="background-color:${statusColors[point.status] || '#95a5a6'}">
                                        ${point.status}
                                    </span>
                                </div>
                                
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Prioridad:</span>
                                    <span class="tooltip-value">${point.priority || 'Media'}</span>
                                </div>
                                
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Planificado:</span>
                                    <span class="tooltip-value">
                                        ${Highcharts.dateFormat('%A, %e %b %Y', point.start)}<br>
                                        a ${Highcharts.dateFormat('%A, %e %b %Y', point.end)}<br>
                                        (${daysBetween(point.start, point.end)} días)
                                    </span>
                                </div>
                                
                                ${point.realStart ? `
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Real:</span>
                                    <span class="tooltip-value">
                                        ${Highcharts.dateFormat('%A, %e %b %Y', point.realStart)}<br>
                                        a ${Highcharts.dateFormat('%A, %e %b %Y', point.realEnd)}<br>
                                        ${diffText}
                                    </span>
                                </div>
                                ` : ''}
                                
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Progreso:</span>
                                    <span class="tooltip-value ${progressClass}">
                                        ${progress}% completado
                                    </span>
                                </div>
                                
                                <div class="progress-comparison">
                                    <div class="progress-bar-plan" style="width: 100%"></div>
                                    ${point.realStart ? `
                                    <div class="progress-bar-real" style="width: ${progress}%"></div>
                                    ` : ''}
                                </div>
                                
                                ${point.details ? `
                                <div class="tooltip-section">
                                    <span class="tooltip-label">Detalles:</span>
                                    <span class="tooltip-value">${point.details}</span>
                                </div>
                                ` : ''}
                            </div>
                        `;
                    }
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: false
                        },
                        pointPadding: 0.2,
                        groupPadding: 0.3
                    },
                    gantt: {
                        pointWidth: 12
                    }
                },
                series: [{
                    name: 'Planificado',
                    data: [
                        // Ana Martínez (y: 0) - Múltiples actividades
                        {
                            name: 'Diseño UI',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            y: 0,
                            status: 'Completada',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Alta',
                            details: 'Diseño de interfaces para nuevo módulo',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            realEnd: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1, 18),
                            realProgress: 100
                        },
                        {
                            name: 'Revisión requisitos',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            y: 0,
                            status: 'En progreso',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Media',
                            details: 'Revisión con stakeholders',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            realProgress: 60
                        },
                        {
                            name: 'Pruebas usuario',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+4),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+5),
                            y: 0,
                            status: 'Pendiente',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Alta',
                            details: 'Sesiones de prueba con usuarios finales'
                        },
                        
                        // Carlos Rodríguez (y: 1) - Múltiples actividades
                        {
                            name: 'Desarrollo API',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            y: 1,
                            status: 'En progreso',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Alta',
                            details: 'Desarrollo endpoints principales',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            realProgress: 75
                        },
                        {
                            name: 'Reunión cliente',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3, 10),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3, 12),
                            y: 1,
                            status: 'Pendiente',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Crítica',
                            details: 'Presentación de avances'
                        },
                        
                        // María González (y: 2) - Múltiples actividades
                        {
                            name: 'Documentación',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+4),
                            y: 2,
                            status: 'Atrasada',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Media',
                            details: 'Manual de usuario y API',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            realProgress: 20
                        },
                        {
                            name: 'Capacitación',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+5),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+5),
                            y: 2,
                            status: 'Pendiente',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Baja',
                            details: 'Entrenamiento equipo soporte'
                        },
                        
                        // Juan Pérez (y: 3) - Múltiples actividades
                        {
                            name: 'Optimización DB',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            y: 3,
                            status: 'Completada',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Alta',
                            details: 'Revisión de índices y consultas',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            realEnd: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1, 16),
                            realProgress: 100
                        },
                        {
                            name: 'Mantenimiento',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+4),
                            y: 3,
                            status: 'En riesgo',
                            color: '#3498db',
                            className: 'highcharts-point-plan',
                            priority: 'Media',
                            details: 'Actualización de servidores',
                            realStart: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            realProgress: 30
                        }
                    ]
                }, {
                    name: 'Real',
                    pointWidth: 6,
                    data: [
                        // Ana Martínez (y: 0)
                        {
                            name: 'Diseño UI (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1, 18),
                            y: 0,
                            status: 'Completada',
                            color: '#2ecc71'
                        },
                        {
                            name: 'Revisión requisitos (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2, 16),
                            y: 0,
                            status: 'En progreso',
                            color: '#2ecc71'
                        },
                        
                        // Carlos Rodríguez (y: 1)
                        {
                            name: 'Desarrollo API (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+2, 18),
                            y: 1,
                            status: 'En progreso',
                            color: '#2ecc71'
                        },
                        
                        // María González (y: 2)
                        {
                            name: 'Documentación (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3, 15),
                            y: 2,
                            status: 'Atrasada',
                            color: '#e74c3c'
                        },
                        
                        // Juan Pérez (y: 3)
                        {
                            name: 'Optimización DB (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+1, 16),
                            y: 3,
                            status: 'Completada',
                            color: '#2ecc71'
                        },
                        {
                            name: 'Mantenimiento (Real)',
                            start: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3),
                            end: Date.UTC(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate()+3, 12),
                            y: 3,
                            status: 'En riesgo',
                            color: '#f39c12'
                        }
                    ]
                }]
            });
    });
</script>

<?php
include(HTML . 'AdminPanel/masterPanel/foot.php');
?>