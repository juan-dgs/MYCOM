<?php

$q_usu = "";
if (USER_TYPE!='SPUS'){
    $q_usu = " AND a.id_usuario_resp = '".USER_ID."' ";
}


$modo = $db->real_escape_string($_POST['modo']);
if($modo==''){
    $modo = 'USUA';
}

$periodo = $db->real_escape_string($_POST['periodo']);


try {
    $fecha_inicio = '2025-04-01';
    $resultados = $db->callProcedure('sp_preparar_base_actividades', [$fecha_inicio]);
    
    // Ahora puedes trabajar con la tabla temporal
    $consulta = $db->query("SELECT * FROM temp_base_actividades");
    
    if ($consulta) {       
    }else{
        echo "Error: Procedimiento Almacenado.";
        return 0;
    }




/*$Q_BASE ="SELECT 
            a.folio,a.id_usuario_resp,a.fh_captura,a.f_plan_i,a.f_plan_f,p.hr_min,p.hr_max,a.fh_finaliza,a.c_tipo_act,a.c_clasifica_act,a.c_prioridad,a.c_estatus,a.calificacion,a.avance,a.id_cliente,
              if(f_plan_f is null,
                p.hr_max,
                fn_calcular_horas_laborables(
                    IFNULL(a.f_plan_i, a.fh_captura),
                    IFNULL(a.f_plan_f, DATE_ADD(fh_captura, INTERVAL p.hr_max HOUR))
                )) AS horas_plan,
                
            fn_calcular_horas_laborables(
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
            WHERE (a.fh_captura > '2025-04-01' OR a.c_estatus ='A' OR a.fh_finaliza > '2025-04-01') AND a.c_estatus !='X' $q_usu";*/

$Q_BASE =  " temp_base_actividades ";


$q_tablero = "";

switch ($modo) {
    case 'USUA':
        $q_tablero = "SELECT  u.id,concat(u.nombre,' ',u.apellido_p,' ',u.apellido_m) as nombre,u.dir_foto,
                                sum(horas_plan) as total_plan,
                                sum(horas_real) as total_real,
                                SUM(1) as total_act,
                                SUM(if(c_estatus='F',1,0)) as tot_fin,
                                SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
                                SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
                                AVG(avance) as avance_prom
                                from $Q_BASE as calculo 
                                    LEFT JOIN users as u on calculo.id_usuario_resp =u.id 
                                    GROUP BY u.id 
                                    ORDER BY avance_prom DESC;";
        break;
    case 'TIPO':
        $q_tablero = "SELECT c_tipo_act as id,t.descripcion as nombre,
                                        sum(horas_plan) as total_plan,
                                        sum(horas_real) as total_real,
                                        SUM(1) as total_act,
                                        SUM(if(c_estatus='F',1,0)) as tot_fin,
                                        SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
                                        SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
                                        AVG(avance) as avance_prom
                                        from $Q_BASE as calculo 
                                            LEFT JOIN act_c_tipos as t on calculo.c_tipo_act = t.codigo
                                            GROUP BY c_tipo_act
                                            ORDER BY avance_prom DESC;";


        break;
    case 'CLAS':
         $q_tablero = "SELECT c_clasifica_act as id,c.descripcion as nombre, 
                                sum(horas_plan) as total_plan,
                                sum(horas_real) as total_real,
                                SUM(1) as total_act,
                                SUM(if(c_estatus='F',1,0)) as tot_fin,
                                SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
                                SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
                                AVG(avance) as avance_prom
                           from $Q_BASE as calculo 
                                    LEFT JOIN act_c_clasificacion as c on calculo.c_clasifica_act = c.codigo
                                    GROUP BY c_clasifica_act
                                    ORDER BY avance_prom DESC;";
        break;
    case 'PRIO':
        $q_tablero = "SELECT   c_prioridad as id,p.descripcion as nombre, 
        sum(horas_plan) as total_plan,
        sum(horas_real) as total_real,
        SUM(1) as total_act,
        SUM(if(c_estatus='F',1,0)) as tot_fin,
        SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
        SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
        AVG(avance) as avance_prom
        from $Q_BASE as calculo 
            LEFT JOIN act_c_prioridades as p on calculo.c_prioridad = p.codigo
            GROUP BY c_prioridad 
            ORDER BY avance_prom DESC;";
        break;
    case 'CLIE':
        $q_tablero = "SELECT c.alias as id,c.razon_social as nombre,
        sum(horas_plan) as total_plan,
        sum(horas_real) as total_real,
        SUM(1) as total_act,
        SUM(if(c_estatus='F',1,0)) as tot_fin,
        SUM(if(c_estatus='F' and horas_plan>=horas_real,1,0)) as tot_cumple_sla,
        SUM(if(horas_plan<horas_real,1,0)) as tot_atrasos,
        AVG(avance) as avance_prom
    from $Q_BASE as calculo 
            LEFT JOIN act_c_clientes as c on calculo.id_cliente = c.id
            GROUP BY id_cliente
            ORDER BY avance_prom DESC;";
        break;
    default:
        $modo="USUA";
}

$HTML='';
//$HTML =str_replace("<", "°", $q_tablero);

$id = "id";
$sql = $db->query($q_tablero);
if ($db->rows($sql) > 0) {
    while ($data = $db->recorrer($sql)) {
        // Eliminar índices numéricos y conservar solo los asociativos
        /*$datos_filtrados = array_filter($data, function($key) {
            return !is_numeric($key); // Conserva solo claves no numéricas
        }, ARRAY_FILTER_USE_KEY);*/
        
        $dt_register[$data[$id]] = $data;
    }
}else{
  $dt_register=false;
}
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    return 0;
}


$HTML .='<div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" id="btnClasUSUA" class="btn '.($modo=='USUA'?'btn-primary':'btn-default').' clasUSUA" onclick="getTablaEficiencia(\'USUA\');">Usuario</button>
            <button type="button" id="btnClasTIPO" class="btn '.($modo=='TIPO'?'btn-primary':'btn-default').' clasTIPO" onclick="getTablaEficiencia(\'TIPO\');">Tipo Actividad</button>
            <button type="button" id="btnClasCLAS" class="btn '.($modo=='CLAS'?'btn-primary':'btn-default').' clasCLAS" onclick="getTablaEficiencia(\'CLAS\');">Clasificación</button>
            <button type="button" id="btnClasPRIO" class="btn '.($modo=='PRIO'?'btn-primary':'btn-default').' clasPRIO" onclick="getTablaEficiencia(\'PRIO\');">Prioridad</button>
            <button type="button" id="btnClasCLIE" class="btn '.($modo=='CLIE'?'btn-primary':'btn-default').' clasCLIE" onclick="getTablaEficiencia(\'CLIE\');">Clientes</button>
        </div>';

if (!empty($dt_register)) {
    $HTML .='<table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 45px">ID</th>
                                <th class="detalle text-center">Descripción</th>                           
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

                                $porSLA =(($dt_register[$id]["total_act"] != 0)? round($dt_register[$id]["tot_cumple_sla"] / $dt_register[$id]["total_act"]*100, 2):"0");

                                
                                $HTML .='<tr>
                                        <td>'.($modo=='USUA'?($dt_register[$id]['id'] != '' ? '<div title="Usuario Responsable: ' . $dt_register[$id]['nombre'] . '" class="circular" style="background: url(views/images/profile/' . ($dt_register[$id]['dir_foto'] != '' ? $dt_register[$id]['dir_foto'] : 'userDefault.png') . ');  background-size:  cover; width:30px; height: 30px;  border: solid 2px #fff; "></div>':''):$id).'</td>
                                        <td class="detalle text-left">'.$dt_register[$id]["nombre"].'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_plan"],0).' hrs</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_real"],0).' hrs</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["total_act"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_fin"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_cumple_sla"],0).'</td>
                                        <td class="detalle text-right">'.round($dt_register[$id]["tot_atrasos"],0).'</td>
                                        <td class="resumen text-right" title="Suma de horas laborables Plan vs Suma de horas laborales real">'.round($dt_register[$id]["total_plan"],0).' hrs vs '.round($dt_register[$id]["total_real"],0).' hrs</td>
                                        <td><span class="badge badge-por" por="'.$porSLA.'">'.$porSLA.' %</span></td>
                                        <td><div class="progress progress-xs"><div class="progress-bar  progress-bar-striped active" avance="'.round($dt_register[$id]["avance_prom"],2).'" ></div></div></td>
                                        <td><span class="badge badge-por" por="'.round($dt_register[$id]["avance_prom"],2).'">'.round($dt_register[$id]["avance_prom"],2).'%</span></td>
                                    </tr>';
                        }
                       
//style="width:'.round($dt_register[$id]["avance_prom"],2).'%"
                           $HTML .='</tbody>
                    </table>';
                    $db->liberar($consulta);

} else {
   

    $HTML .= 'ERROR: NO HAY RESULTADOS..';
}
echo $HTML;

?>
