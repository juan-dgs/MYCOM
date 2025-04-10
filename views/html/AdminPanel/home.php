<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views/js/repository.js"></script>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
    #container-graph {
        height: 400px;
    }

    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    .highcharts-description {
        margin: 0.3rem 10px;
    }


    .card .resumen {
        display: table-cell !important;
    }

    .card .detalle {
        display: none !important;
    }

    .card.maximized-card .resumen {
        display: none !important;
    }

    .card.maximized-card .detalle {
        display: table-cell !important;

    }

    .card:not(.maximized-card) #contEficiencia {
        height: 500px;
        overflow: auto;
    }
</style>

<!--
******para numeros generales
SELECT sum(IF(c_estatus='X',1,0))as cancelados, sum(IF(c_estatus='F',1,0))as finalizados, sum(IF(c_estatus='A',1,0))as pendientes, sum(IF(c_estatus='A' and avance > 5,1,0))as enproceso FROM `actividades` WHERE (fh_captura > '2025-04-01' OR c_estatus ='A' OR fh_finaliza > '2025-04-01');

******para horas plan

SELECT u.id,concat(u.nombre,' ',u.apellido_p) as usuario,u.dir_foto,
sum(horas_plan) as prom_plan,
sum(horas_real) as prom_real,
SUM(1) as tot_act,
SUM(if(c_estatus='F',1,0)) as tot_fin,
SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
AVG(avance) as avance_prom
from (

    
SELECT 
   a.folio,a.id_usuario_resp,a.fh_captura,a.f_plan_i,a.f_plan_f,p.hr_min,p.hr_max,a.fh_finaliza,a.c_tipo_act,a.c_clasifica_act,a.c_prioridad,a.c_estatus,a.calificacion,a.avance,
   
 if(f_plan_f is null,
    p.hr_max,
 	fn_horas_laborables_dinamico(
        IFNULL(a.f_plan_i, a.fh_captura),
        IFNULL(a.f_plan_f, DATE_ADD(fh_captura, INTERVAL p.hr_max HOUR))
    )) AS horas_plan,
    


 fn_horas_laborables_dinamico(
        IFNULL(a.f_plan_i, a.fh_captura),
        IFNULL(a.fh_finaliza, now())
    ) AS horas_real,
    
        ROUND(TIMESTAMPDIFF(SECOND, 
          IFNULL(a.f_plan_i, a.fh_captura), 
          IFNULL(a.f_plan_f, DATE_ADD(fh_captura, INTERVAL p.hr_max HOUR))) / 3600.0, 2) AS horas_totales_plan,
          
    ROUND(TIMESTAMPDIFF(SECOND, 
          IFNULL(a.f_plan_i, a.fh_captura), 
          IFNULL(a.fh_finaliza, now())) / 3600.0, 2) AS horas_totales_real


FROM actividades a LEFT JOIN 
	act_c_prioridades as p on p.codigo = a.c_prioridad
WHERE (a.fh_captura > '2025-04-01' OR a.c_estatus ='A' OR a.fh_finaliza > '2025-04-01')
    
    ) as calculo 
    LEFT JOIN users as u on calculo.id_usuario_resp =u.id 
    
    GROUP BY u.id 
    ORDER BY tot_act DESC;
-->



<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id='countPendientes'>150</h3>
                <p>En Progreso</p>
            </div>
            <div class="icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <a class="small-box-footer" id="detPendientes"></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id='countFinalizadas'>53</h3>

                <p>Completadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <a class="small-box-footer" id="detFinalizadas"></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="countAtrasadas">44</h3>

                <p>Atrasadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-down"></i>
            </div>
            <a class="small-box-footer" id="detAtrasadas"></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="coutCumplimiento">65%</h3>

                <p>Cumplimiento SLA</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <a class="small-box-footer" id="detCumplimiento"></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row">
    <div class="col-lg-4 col-6">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Resumen Cumplimiento</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-bod p-0" id="contEficiencia">
            </div>
            <!-- /.card-body -->
        </div>


    </div>
    <div class="col-lg-8 col-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Avance Anual</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <figure class="highcharts-figure">
                    <div id="container-graph"></div>
                    <p class="highcharts-description">
                    </p>
                </figure>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getTablaEficiencia('USUA');
        getContadores();

        Highcharts.chart('container-graph', {
            chart: {
                zooming: {
                    type: 'xy'
                }
            },
            title: {
                text: 'Average Monthly Weather Data for Tokyo'
            },
            subtitle: {
                text: 'Source: WorldClimate.com'
            },
            xAxis: [{
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ],
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}°C',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                title: {
                    text: 'Temperature',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                opposite: true

            }, { // Secondary yAxis
                gridLineWidth: 0,
                title: {
                    text: 'Rainfall',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} mm',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }

            }, { // Tertiary yAxis
                gridLineWidth: 0,
                title: {
                    text: 'Sea-Level Pressure',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    format: '{value} mb',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 55,
                floating: true,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Rainfall',
                type: 'column',
                yAxis: 1,
                data: [
                    49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1,
                    95.6, 54.4
                ],
                tooltip: {
                    valueSuffix: ' mm'
                }

            }, {
                name: 'Sea-Level Pressure',
                type: 'spline',
                yAxis: 2,
                data: [
                    1016, 1016, 1015.9, 1015.5, 1012.3, 1009.5, 1009.6, 1010.2, 1013.1,
                    1016.9, 1018.2, 1016.7
                ],
                marker: {
                    enabled: false
                },
                dashStyle: 'shortdot',
                tooltip: {
                    valueSuffix: ' mb'
                }

            }, {
                name: 'Temperature',
                type: 'spline',
                data: [
                    7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6
                ],
                tooltip: {
                    valueSuffix: ' °C'
                }
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            floating: false,
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom',
                            x: 0,
                            y: 0
                        },
                        yAxis: [{
                            labels: {
                                align: 'right',
                                x: 0,
                                y: -6
                            },
                            showLastLabel: false
                        }, {
                            labels: {
                                align: 'left',
                                x: 0,
                                y: -6
                            },
                            showLastLabel: false
                        }, {
                            visible: false
                        }]
                    }
                }]
            }
        });

    });

    var _CARGANDO = '<div class="cargando-spiner">' +
        '<i class="fa fa-spinner fa-spin fa-3x"></i>' +
        '</div>';



    function getTablaEficiencia(modo) {
        var periodo = '';

        $.ajax({
            url: "ajax.php?mode=gettableroeficiencia",
            type: "POST",
            data: {
                modo: modo,
                periodo: periodo
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {
                $('#contEficiencia').html(_CARGANDO);

                setTimeout(function() {
                    $(".progress-bar").each(function() {
                        //animarProgressBar($(this), parseFloat($(this).attr("avance")));
                        animarProgressBarDegradado($(this), parseFloat($(this).attr("avance")), {
                                duracion: 1500,
                                colores: [
                                    { porcentaje: 0, color: '#ff0000' },   // Rojo
                                    { porcentaje: 30, color: '#ff8000' },  // Naranja
                                    { porcentaje: 70, color: '#ffff00' },  // Amarillo
                                    { porcentaje: 100, color: '#28a745' }  // Verde
                                ],
                                onComplete: function() {
                                    console.log('Animación completada');
                                }
                            });
                    });

                    $(".badge-por").each(function() {
                        animarBadgeDegradado($(this), $(this).attr("por"), {
                            colorInicio: '#ff0000',
                            colorMedio: '#ff8000',  // Naranja
                            colorFin: '#28a745',
                            duracion: 1500
                        });
                    });



                }, 500);


            },
            success: function(datos) {
                $("#contEficiencia").html(datos);
            }
        });
    }

    function animarBadgeDegradado($element, porcentajeFinal, opciones = {}) {
    const config = {
        duracion: 2000,
        colorInicio: '#ff0000',  // Rojo
        colorMedio: '#ffff00',   // Amarillo
        colorFin: '#00ff00',     // Verde
        onComplete: null,
        ...opciones
    };

    let start = null;
    const badgeOriginal = $element.text();
    
    function animar(timestamp) {
        if (!start) start = timestamp;
        const progreso = Math.min((timestamp - start) / config.duracion, 1);
        const porcentaje = Math.round(progreso * porcentajeFinal);
        
        // Actualizar texto
        $element.text(`${porcentaje}%`);
        
        // Calcular color intermedio
        let color;
        if (porcentaje < 50) {
            const factor = porcentaje / 50;
            color = interpolateColor(config.colorInicio, config.colorMedio, factor);
        } else {
            const factor = (porcentaje - 50) / 50;
            color = interpolateColor(config.colorMedio, config.colorFin, factor);
        }
        
        // Aplicar color
        $element.css('background-color', color);
        
        // Continuar o finalizar
        if (progreso < 1) {
            requestAnimationFrame(animar);
        } else if (typeof config.onComplete === 'function') {
            config.onComplete();
        }
    }
    
    // Función para interpolar colores HEX
    function interpolateColor(color1, color2, factor) {
        const r1 = parseInt(color1.substring(1, 3), 16);
        const g1 = parseInt(color1.substring(3, 5), 16);
        const b1 = parseInt(color1.substring(5, 7), 16);
        
        const r2 = parseInt(color2.substring(1, 3), 16);
        const g2 = parseInt(color2.substring(3, 5), 16);
        const b2 = parseInt(color2.substring(5, 7), 16);
        
        const r = Math.round(r1 + (r2 - r1) * factor);
        const g = Math.round(g1 + (g2 - g1) * factor);
        const b = Math.round(b1 + (b2 - b1) * factor);
        
        return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
    }
    
    // Iniciar animación
    requestAnimationFrame(animar);
}

    function animarProgressBarDegradado($element, objetivo, config = {}) {
        const defaults = {
            duracion: 2000,
            colores: [{
                    porcentaje: 0,
                    color: '#ff0000'
                }, // Rojo
                {
                    porcentaje: 50,
                    color: '#ffff00'
                }, // Amarillo
                {
                    porcentaje: 100,
                    color: '#00ff00'
                } // Verde
            ],
            onComplete: null
        };

        config = $.extend({}, defaults, config);

        let start = null;
        const $bar = $element;
        $bar.css('width', '0%');

        function mezclarColores(color1, color2, factor) {
            const result = color1.slice();
            for (let i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - result[i]));
            }
            return result;
        }

        function hexARgb(hex) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return [r, g, b];
        }

        function animar(timestamp) {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / config.duracion, 1);
            const porcentaje = progress * objetivo;

            $bar.css('width', porcentaje + '%');

            // Calcular color basado en el porcentaje
            for (let i = 1; i < config.colores.length; i++) {
                if (porcentaje <= config.colores[i].porcentaje) {
                    const color1 = hexARgb(config.colores[i - 1].color);
                    const color2 = hexARgb(config.colores[i].color);
                    const factor = (porcentaje - config.colores[i - 1].porcentaje) /
                        (config.colores[i].porcentaje - config.colores[i - 1].porcentaje);

                    const color = mezclarColores(color1, color2, factor);
                    $bar.css('background-color', `rgb(${color[0]}, ${color[1]}, ${color[2]})`);
                    break;
                }
            }

            if (progress < 1) {
                requestAnimationFrame(animar);
            } else if (typeof config.onComplete === 'function') {
                config.onComplete();
            }
        }

        requestAnimationFrame(animar);
    }


    function getContadores() {
        var periodo = '';

        $.ajax({
            url: "ajax.php?mode=getcontadores",
            type: "POST",
            data: {
                periodo: periodo
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {

            },
            success: function(datos) {
                var result = JSON.parse(datos);
                //console.log(result);


                $('#countPendientes').contadorAnimado({
                    target: parseInt(result[1]["pendientes"]),
                    duration: 1000,
                    decimals: 0,
                    prefix: '',
                    suffix: '',
                    onComplete: function() {}
                });

                $("#detPendientes").contadorAnimado({
                    target: parseInt(parseInt(result[1]["pendientes"]) / parseInt(result[1]["tot_act"]) * 100),
                    duration: 1500,
                    decimals: 0,
                    prefix: '',
                    suffix: '% ',
                    onComplete: function() {
                        $('#detPendientes').append(" <b title='" + result[1]["tot_act"] + "'>del Total</b>")
                    }
                });

                $('#countAtrasadas').contadorAnimado({
                    target: parseInt(result[1]["atrasadas"]),
                    duration: 1000,
                    decimals: 0,
                    prefix: '',
                    suffix: '',
                    onComplete: function() {}
                });

                $('#detAtrasadas').contadorAnimado({
                    target: parseInt(parseInt(result[1]["atrasadas"]) / parseInt(result[1]["pendientes"]) * 100),
                    duration: 1500,
                    decimals: 0,
                    prefix: '',
                    suffix: '% ',
                    onComplete: function() {
                        $('#detAtrasadas').append(" <b title='" + result[1]["pendientes"] + "'>del las Pendientes</b>")
                    }
                });

                $('#countFinalizadas').contadorAnimado({
                    target: parseInt(result[1]["finalizadas"]),
                    duration: 1000,
                    decimals: 0,
                    prefix: '',
                    suffix: '',
                    onComplete: function() {}
                });

                $('#detFinalizadas').contadorAnimado({
                    target: parseInt(result[1]["avance_prom"]),
                    duration: 1500,
                    decimals: 0,
                    prefix: '',
                    suffix: '% ',
                    onComplete: function() {
                        $('#detFinalizadas').append(" Avance Promedio")
                    }
                });


                $('#coutCumplimiento').contadorAnimado({
                    target: parseInt((parseInt(result[1]["cumplimiento_SLA"]) / parseInt(result[1]["tot_act"])) * 100),
                    duration: 1000,
                    decimals: 0,
                    prefix: '',
                    suffix: '% ',
                    onComplete: function() {}
                });

                $('#detCumplimiento').contadorAnimado({
                    target: parseInt(parseInt(result[1]["cumplimiento_SLA"])),
                    duration: 1500,
                    decimals: 0,
                    prefix: '',
                    suffix: '',
                    onComplete: function() {
                        $("#detCumplimiento").append(' SLA Cumplidos de un total de ' + result[1]["tot_act"]);
                    }
                });


            }
        });
    }


    /**
     * Contador animado que inicia desde 0 hasta el valor objetivo con formato
     * @param {Object} options - Opciones de configuración
     */
    $.fn.contadorAnimado = function(options) {
        const settings = $.extend({
            target: 100, // Valor final del contador
            duration: 2000, // Duración en milisegundos
            decimals: 0, // Decimales a mostrar
            prefix: '', // Prefijo (ej: '$')
            suffix: '', // Sufijo (ej: '%')
            separadorMiles: true, // Mostrar separadores de miles
            easing: 'swing', // Tipo de easing
            onComplete: null // Callback al finalizar
        }, options);

        return this.each(function() {
            const $this = $(this);
            $this.text('0'); // Inicializar en 0

            const start = 0; // Siempre comenzará desde 0
            const range = settings.target - start;
            const stepTime = Math.abs(Math.floor(settings.duration / range));

            let current = start;
            const timer = setInterval(() => {
                current += range > 0 ? 1 : -1;

                // Formatear el número con separadores de miles y decimales
                let displayValue;
                if (settings.decimals > 0) {
                    displayValue = current.toFixed(settings.decimals);
                    if (settings.separadorMiles) {
                        const parts = displayValue.split('.');
                        parts[0] = parseInt(parts[0]).toLocaleString();
                        displayValue = parts.join('.');
                    }
                } else {
                    displayValue = settings.separadorMiles ?
                        Math.floor(current).toLocaleString() :
                        Math.floor(current).toString();
                }

                $this.text(settings.prefix + displayValue + settings.suffix);

                if (current === settings.target) {
                    clearInterval(timer);
                    if (typeof settings.onComplete === 'function') {
                        settings.onComplete.call(this);
                    }
                }
            }, stepTime);
        });
    };
</script>




<?php include(HTML . 'AdminPanel/masterPanel/foot.php'); ?>