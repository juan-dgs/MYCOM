<?php

$q_usu = "";
if (USER_TYPE!='SPUS'){
    $q_usu = " AND id_usuario_resp = '".USER_ID."' ";
}

/****************************DATOS PARA GRAFICA DE PASTEL*******************************/

$qt = "SELECT c_tipo_act as cod,t.descripcion as tipo,color_hex,SUM(1) as cuantos 
                FROM actividades as a 
                LEFT JOIN act_c_tipos as t on t.codigo = a.c_tipo_act
                WHERE fh_captura >= '2025-01-01' AND c_estatus !='X' $q_usu
        GROUP BY c_tipo_act 
        ORDER BY c_tipo_act;";

$dt_contadorest = findtablaq($qt, "cod");

$data_tipo_colors = [];
$data_tipo = array();
$data_categoria = array();

if (!empty($dt_contadorest)) {
    foreach ($dt_contadorest as $id => $array) {
        $data_tipo[] = array(
            'name' => $dt_contadorest[$id]['tipo'],
            'y' => intval($dt_contadorest[$id]['cuantos'])
        );
    }


    
    foreach ($dt_contadorest as $id => $array) {
        $data_tipo_colors[$dt_contadorest[$id]['tipo']] = $dt_contadorest[$id]['color_hex'];
    }
}

$q = "SELECT concat(c_tipo_act,c_clasifica_act) as cod,c_tipo_act,t.descripcion as tipo,c_clasifica_act,c.descripcion as clasifica,SUM(1) as cuantos 
                FROM actividades as a 
                LEFT JOIN act_c_tipos as t on t.codigo = a.c_tipo_act
                LEFT JOIN act_c_clasificacion as c on c.codigo = a.c_clasifica_act
                WHERE fh_captura >= '2025-01-01' AND c_estatus !='X' $q_usu
        GROUP BY c_tipo_act,c_clasifica_act 
        ORDER BY c_tipo_act;";

$dt_contadores = findtablaq($q, "cod");


if (!empty($dt_contadores)) {
    foreach ($dt_contadores as $id => $array) {
        $data_categoria[] = array(
            'name' => $dt_contadores[$id]['tipo'] . ' ' . $dt_contadores[$id]['clasifica'],
            'y' => intval($dt_contadores[$id]['cuantos']),
            'custom' => array(
                'clasificacion' => $dt_contadores[$id]['clasifica'],
                'clasifica_act' => $data_categoria
            )
        );
    }
}



$pie = ([
    'tipos_act' => $data_tipo,
    'clasifica_act' => $data_categoria,
    'tipos_act_colors' => $data_tipo_colors
]);

/****************************DATOS PARA GRAFICA DE BARRAS*******************************/

$series= [];
$meses= [];

foreach ($_MESES as $indice =>  $valor) {
    if($valor!=''){
        $meses[]=$valor;
    }
}

try {
    $fecha_inicio = '2025-04-01';
    $resultados = $db->callProcedure('sp_preparar_base_actividades', [$fecha_inicio]);

    $consulta = $db->query("SELECT * FROM temp_base_actividades");

    if ($consulta) {
    } else {
        echo "Error: Procedimiento Almacenado.";
        return 0;
    }

$q_bar ="SELECT ";
    foreach ($meses as $indice =>  $valor) {
        $q_bar .=" SUM(if(MONTH(fh_captura)=$indice,1,0)) as cap_$valor,
                    SUM(if(c_estatus='F' AND MONTH(fh_finaliza)=$indice,1,0)) as fin_$valor,
                    SUM(if(c_estatus='F' AND MONTH(IFNULL(f_plan_i,fh_finaliza))=$indice and horas_plan>=horas_real,1,0)) as sla_$valor,
                    SUM(if(horas_plan<horas_real AND MONTH(IFNULL(f_plan_i,fh_finaliza))=$indice,1,0)) as atr_$valor,";
            }

    $q_bar .=" 1 as id FROM temp_base_actividades as b WHERE 1=1 $q_usu;";

    $sql = $db->query($q_bar);
    if ($db->rows($sql) > 0) {
        while ($data = $db->recorrer($sql)) {
            $dt_series[$data["id"]] = $data;
        }
    } else {
        $dt_series = false;
    }

    if (!empty($dt_series)) {
        foreach ($dt_series as $id => $array) {
            $arr_reg = [];
            $arr_fin = [];
            $arr_SLA = [];
            $arr_atr = [];

            foreach ($meses as $indice =>  $valor) {
                if($indice>0){
                    $arr_reg[] = intval($dt_series[$id]["cap_".$valor]);
                    $arr_fin[] = intval($dt_series[$id]["fin_".$valor]);
                    $arr_SLA[] = intval($dt_series[$id]["sla_".$valor]);
                    $arr_atr[] = intval($dt_series[$id]["atr_".$valor]);    
                }
            }

            

            $series[] = array(
                'name' => "Actividades Registradas",
                'data' => $arr_reg ,
                'color' => '#007BFF'             
            );
            $series[] = array(
                'name' => "Actividades Finalizadas",
                'data' => $arr_fin,
                'color' => '#0a7a4a'               
            );
            $series[] = array(
                'name' => "Actividades Cumplen SLA",
                'data' => $arr_SLA,
                'color' => '#28A745'            
            );
            $series[] = array(
                'name' => "Actividades Atrasadas",
                'data' => $arr_atr,
                'color' => '#DC3545'         
            );



        }
    }else{
        echo json_encode(['test'=>$q_bar]);
            return 0;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    return 0;
}


$bar = ([
    'meses' => $meses,
    'series' => $series
]);


echo json_encode([
    'pie' => $pie,
    'bar' => $bar
]);
