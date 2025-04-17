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
            <div class="card-body p-0 row">
                <div class='col-xs-12 col-sm-8'>
                    <div id="contenedor-barras"></div>
                </div>
                <div class='col-xs-12 col-sm-4'>
                    <div id="contenedor-pie"></div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getTablaEficiencia('USUA');
        getContadores();
        getGraficas();
    });

    var _CARGANDO = '<div class="cargando-spiner">' +
        '<i class="fa fa-spinner fa-spin fa-3x"></i>' +
        '</div>';

function graphPie(datos){
    const colors = Highcharts.getOptions().colors,
            categories = [
                'Chrome',
                'Safari',
                'Edge',
                'Firefox',
                'Other'
            ],
            data = [{
                    y: 61.04,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome',
                        categories: [
                            'Chrome v97.0',
                            'Chrome v96.0',
                            'Chrome v95.0',
                            'Chrome v94.0',
                            'Chrome v93.0',
                            'Chrome v92.0',
                            'Chrome v91.0',
                            'Chrome v90.0',
                            'Chrome v89.0',
                            'Chrome v88.0',
                            'Chrome v87.0',
                            'Chrome v86.0',
                            'Chrome v85.0',
                            'Chrome v84.0',
                            'Chrome v83.0',
                            'Chrome v81.0',
                            'Chrome v89.0',
                            'Chrome v79.0',
                            'Chrome v78.0',
                            'Chrome v76.0',
                            'Chrome v75.0',
                            'Chrome v72.0',
                            'Chrome v70.0',
                            'Chrome v69.0',
                            'Chrome v56.0',
                            'Chrome v49.0'
                        ],
                        data: [
                            36.89,
                            18.16,
                            0.54,
                            0.7,
                            0.8,
                            0.41,
                            0.31,
                            0.13,
                            0.14,
                            0.1,
                            0.35,
                            0.17,
                            0.18,
                            0.17,
                            0.21,
                            0.1,
                            0.16,
                            0.43,
                            0.11,
                            0.16,
                            0.15,
                            0.14,
                            0.11,
                            0.13,
                            0.12
                        ]
                    }
                },
                {
                    y: 9.47,
                    color: colors[3],
                    drilldown: {
                        name: 'Safari',
                        categories: [
                            'Safari v15.3',
                            'Safari v15.2',
                            'Safari v15.1',
                            'Safari v15.0',
                            'Safari v14.1',
                            'Safari v14.0',
                            'Safari v13.1',
                            'Safari v13.0',
                            'Safari v12.1'
                        ],
                        data: [
                            0.1,
                            2.01,
                            2.29,
                            0.49,
                            2.48,
                            0.64,
                            1.17,
                            0.13,
                            0.16
                        ]
                    }
                },
                {
                    y: 9.32,
                    color: colors[5],
                    drilldown: {
                        name: 'Edge',
                        categories: [
                            'Edge v97',
                            'Edge v96',
                            'Edge v95'
                        ],
                        data: [
                            6.62,
                            2.55,
                            0.15
                        ]
                    }
                },
                {
                    y: 8.15,
                    color: colors[1],
                    drilldown: {
                        name: 'Firefox',
                        categories: [
                            'Firefox v96.0',
                            'Firefox v95.0',
                            'Firefox v94.0',
                            'Firefox v91.0',
                            'Firefox v78.0',
                            'Firefox v52.0'
                        ],
                        data: [
                            4.17,
                            3.33,
                            0.11,
                            0.23,
                            0.16,
                            0.15
                        ]
                    }
                },
                {
                    y: 11.02,
                    color: colors[6],
                    drilldown: {
                        name: 'Other',
                        categories: [
                            'Other'
                        ],
                        data: [
                            11.02
                        ]
                    }
                }
            ],
            browserData = [],
            versionsData = [],
            dataLen = data.length;

        let i,
            j,
            drillDataLen,
            brightness;


        // Build the data arrays
        for (i = 0; i < dataLen; i += 1) {

            // add browser data
            browserData.push({
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });

            // add version data
            drillDataLen = data[i].drilldown.data.length;
            for (j = 0; j < drillDataLen; j += 1) {
                const name = data[i].drilldown.categories[j];
                brightness = 0.2 - (j / drillDataLen) / 5;
                versionsData.push({
                    name,
                    y: data[i].drilldown.data[j],
                    color: Highcharts.color(data[i].color).brighten(brightness).get(),
                    custom: {
                        version: name.split(' ')[1] || name.split(' ')[0]
                    }
                });
            }
        }

        // Create the chart
        Highcharts.chart('contenedor-pie', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Browser market share, January, 2022'
            },
            subtitle: {
                text: 'Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                }
            },
            tooltip: {
                valueSuffix: '%'
            },
            series: [{
                name: 'Browsers',
                data: browserData,
                size: '45%',
                dataLabels: {
                    color: '#ffffff',
                    distance: '-50%'
                }
            }, {
                name: 'Versions',
                data: versionsData,
                size: '80%',
                innerSize: '60%',
                dataLabels: {
                    format: '<b>{point.name}:</b> <span style="opacity: 0.5">' +
                        '{y}%</span>',
                    filter: {
                        property: 'y',
                        operator: '>',
                        value: 1
                    },
                    style: {
                        fontWeight: 'normal'
                    }
                },
                id: 'versions'
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 400
                    },
                    chartOptions: {
                        series: [{}, {
                            id: 'versions',
                            dataLabels: {
                                distance: 10,
                                format: '{point.custom.version}',
                                filter: {
                                    property: 'percentage',
                                    operator: '>',
                                    value: 2
                                }
                            }
                        }]
                    }
                }]
            }
        });

        function crearGrafico(data) {
        // Preparar series para el drilldown
        const drilldownSeries = data.data.map(item => {
            return {
                name: item.drilldown.name,
                id: item.drilldown.name,
                data: item.drilldown.categories.map((category, index) => [
                    category, 
                    item.drilldown.data[index]
                ])
            };
        });

        // Crear el gráfico
        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Distribución de Actividades',
                align: 'center'
            },
            subtitle: {
                text: 'Haz clic en los segmentos para ver detalles',
                align: 'center'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                },
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    innerSize: '50%', // Hace que sea un donut chart
                    depth: 45,
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: 'white',
                            textOutline: '1px contrast'
                        }
                    },
                    showInLegend: true
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">●</span> <b>{point.name}</b>: {point.y}%<br/>'
            },
            series: [{
                name: 'Actividades',
                colorByPoint: true,
                data: data.data.map(item => ({
                    name: item.drilldown.name,
                    y: item.y,
                    color: item.color,
                    drilldown: item.drilldown.name
                }))
            }],
            drilldown: {
                activeDataLabelStyle: {
                    color: '#000000',
                    textOutline: 'none'
                },
                series: drilldownSeries
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        plotOptions: {
                            pie: {
                                innerSize: '30%'
                            }
                        }
                    }
                }]
            }
        });
    }

}

    function getGraficas(id,modo,periodo) {

        $.ajax({
            url: "ajax.php?mode=getdatagraficas",
            type: "POST",
            data: {
                modo: modo,
                id:id,
                periodo: periodo
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {
                $('#contenedor-pie').html(_CARGANDO);            
                $('#contenedor-barra').html(_CARGANDO);            
            },
            success: function(datos) {
               console.log(datos);
               graphPie(datos);
            }
        });

        


        Highcharts.chart('contenedor-barras', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Corn vs wheat estimated production for 2023'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: ['USA', 'China', 'Brazil', 'EU', 'Argentina', 'India'],
                crosshair: true,
                accessibility: {
                    description: 'Countries'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '1000 metric tons (MT)'
                }
            },
            tooltip: {
                valueSuffix: ' (1000 MT)'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Corn',
                    data: [387749, 280000, 129000, 64300, 54000, 34300]
                },
                {
                    name: 'Wheat',
                    data: [45321, 140000, 10000, 140500, 19500, 113500]
                }
            ]
        });


    }


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
                            colores: [{
                                    porcentaje: 0,
                                    color: '#ff0000'
                                }, // Rojo
                                {
                                    porcentaje: 30,
                                    color: '#ff8000'
                                }, // Naranja
                                {
                                    porcentaje: 70,
                                    color: '#ffff00'
                                }, // Amarillo
                                {
                                    porcentaje: 100,
                                    color: '#28a745'
                                } // Verde
                            ],
                            onComplete: function() {
                                console.log('Animación completada');
                            }
                        });
                    });

                    $(".badge-por").each(function() {
                        animarBadgeDegradado($(this), $(this).attr("por"), {
                            colorInicio: '#ff0000',
                            colorMedio: '#ff8000', // Naranja
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
            colorInicio: '#ff0000', // Rojo
            colorMedio: '#ffff00', // Amarillo
            colorFin: '#00ff00', // Verde
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