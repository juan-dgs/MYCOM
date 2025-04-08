<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');
?>

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


    .card .resumen{
        display: table-cell !important;
    }

    .card .detalle{
        display: none !important;
    }
    
    .card.maximized-card .resumen{
        display: none !important;
    }

    .card.maximized-card .detalle{
        display: table-cell !important;
        
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
                <h3>150</h3>

                <p>Pendientes</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
            <a href="#" class="small-box-footer">Más<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>53</h3>

                <p>En progreso</p>
            </div>
            <div class="icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <a href="#" class="small-box-footer">Más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>44</h3>

                <p>Completadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <a href="#" class="small-box-footer">Más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>65</h3>

                <p>Atrasadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-down"></i>
            </div>
            <a href="#" class="small-box-footer">Más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row">
    <div class="col-lg-4 col-6">

        <?php if (USER_TYPE == 'SPUS') { ?>
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
        <?php } else { ?>

            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Alexander Pierce</h3>
                    <h5 class="widget-user-desc">Founder & CEO</h5>
                </div>
                <div class="widget-user-image">
                    <div title="Juan David Garcia" class="circular img-circle elevation-2" style="background: url(views/images/profile/jd.jpg);  background-size:  cover; width:100px; height: 100px;  border: solid 2px #fff; "></div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">SALES</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">13,000</h5>
                                <span class="description-text">FOLLOWERS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->


        <?php } ?>

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
        getTablaEficiencia();


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

    var _CARGANDO = '<div class="cargando-spiner">'+
                        '<i class="fa fa-spinner fa-spin fa-3x"></i>'+
                    '</div>';



    function getTablaEficiencia(){        
        var modo  ='X';
        var periodo ='X';
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
                },
                success: function(datos) {
                    $("#contEficiencia").html(datos);
                }
            });
    }
</script>




<?php include(HTML . 'AdminPanel/masterPanel/foot.php'); ?>