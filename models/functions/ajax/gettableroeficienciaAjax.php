<?php
$modo = $db->real_escape_string($_POST['modo']);
$periodo = $db->real_escape_string($_POST['periodo']);


$dt_register = findtablaq("SELECT u.id,concat(u.nombre,' ',u.apellido_p,' ',u.apellido_m) as nombre,u.dir_foto,
                                        sum(horas_plan) as total_plan,
                                        sum(horas_real) as total_real,
                                        SUM(1) as total_act,
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
                                            ORDER BY avance_prom DESC;", "id");




if ($dt_register != false) {
    $HTML ='<table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 45px">U</th>     
                                <th class="detalle text-center">Nombre</th>                           
                                <th class="detalle text-center">Total hrs<br>Estimado</th>
                                <th class="detalle text-center">Total hrs<br>Real</th>
                                <th class="detalle text-center">Total<br>Actividades</th>
                                <th class="detalle text-center">Total<br>Finalizadas</th>
                                <th class="detalle text-center">Total<br>Cumplen SLAs</th>
                                <th class="detalle text-center">Actividades<br>Atrasadas</th>
                                <th class="resumen text-center" title="horas estimadas vs horas reales [SLAs]">Estimado vs Real</th>
                                <th style="width: 70px">%SLA</th>
                                <th>Avance</th>
                                <th style="width: 40px">%</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach ($dt_register as $id => $array) {
                                $dt_register[$id]["nombre"] = ($dt_register[$id]["nombre"]==''?'Usuario no asignado':$dt_register[$id]["nombre"]);

                                $HTML .='<tr>
                                        <td>'.($dt_register[$id]['id'] != '' ? '<div title="Usuario Responsable: ' . $dt_register[$id]['nombre'] . '" class="circular" style="background: url(views/images/profile/' . ($dt_register[$id]['dir_foto'] != '' ? $dt_register[$id]['dir_foto'] : 'userDefault.png') . ');  background-size:  cover; width:55px; height: 55px;  border: solid 2px #fff; "></div>' : '').'</td>
                                        <td class="detalle text-left">'.$dt_register[$id]["nombre"].'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_plan"],0).' hrs</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_real"],0).' hrs</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_act"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_fin"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_cumple_sla"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_atrasos"],0).'</td>
                                        <td class="resumen text-right" title="Suma de horas laborables Plan vs Suma de horas laborales real">'.round($dt_register[$id]["total_plan"],0).' hrs vs '.round($dt_register[$id]["total_real"],0).' hrs</td>
                                        <td><span class="badge bg-danger">'.(($dt_register[$id]["total_act"] != 0)? round($dt_register[$id]["tot_cumple_sla"] / $dt_register[$id]["total_act"]*100, 2):"0") .' %</span></td>
                                        <td><div class="progress progress-xs"><div class="progress-bar progress-bar-danger" style="width:'.round($dt_register[$id]["avance_prom"],2).'%"></div></div></td>
                                        <td><span class="badge bg-danger">'.round($dt_register[$id]["avance_prom"],2).'%</span></td>
                                    </tr>';
                        }

                           $HTML .='</tbody>
                    </table>';

echo $HTML;
} else {
   

    echo 'ERROR: NO HAY RESULTADOS..';
}
?>
