<?php 
$q_usu = "";
if (USER_TYPE!='SPUS'){
    $q_usu = " AND a.id_usuario_resp = '".USER_ID."' ";
}


$Q_BASE ="SELECT 
            a.folio,a.id_usuario_resp,a.fh_captura,a.f_plan_i,a.f_plan_f,p.hr_min,p.hr_max,a.fh_finaliza,a.c_tipo_act,a.c_clasifica_act,a.c_prioridad,a.c_estatus,a.calificacion,a.avance,a.id_cliente,
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
            WHERE (a.fh_captura > '2025-04-01' OR a.c_estatus ='A' OR a.fh_finaliza > '2025-04-01')  AND a.c_estatus !='X' $q_usu";

            $q = "SELECT 1 AS id,
                        sum(horas_plan) as prom_plan,
                        sum(horas_real) as prom_real,
                        SUM(1) as tot_act,
                        SUM(if(c_estatus='A',1,0)) as pendientes,
                        AVG(avance) as avance_prom,
                        SUM(if(c_estatus='F',1,0)) as finalizadas,
                        SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as cumplimiento_SLA,
                        SUM(if(horas_plan<horas_real,1,0)) as atrasadas
                    FROM (".$Q_BASE.') as calculo 
                    ORDER BY tot_act DESC;';

      $dt_register = findtablaq($q, "id");

      echo json_encode($dt_register);
      ?>