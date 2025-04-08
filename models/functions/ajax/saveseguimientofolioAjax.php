<?php
$db = new Conexion();

$folio = $db->real_escape_string($_POST['folio']);

$comentarios = $db->real_escape_string($_POST['comentarios']);
$avance = $db->real_escape_string($_POST['avance']);

$dt_valuavance=findtablaq("SELECT 1 as id,avance FROM actividades where folio='$folio' LIMIT 1;","id");
$arr = array('codigo' => 0, 'alerta' => 'VALIDACIONES');

if(empty($dt_valuavance)){
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> No se encontro el Folio Seleccionado.');
}else{
    $u_avance =$dt_valuavance[1]['avance'];

    if($u_avance>$avance) {
        $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> No es posbile guardar si el avance es menor que el avance anterior.('.$u_avance.')');
    }else{
        $insert ="INSERT INTO act_r_comentarios 
                    (folio_act, id_u_registra, fh_registra, comentario, avance, visto_por) VALUES 
                    ('$folio', '".USER_ID."', now(), '$comentarios', '$avance', '*".USER_ID."*');";

        $insert.="UPDATE actividades SET avance = '$avance' WHERE folio = '$folio';";

        $db->multi_query($insert);
        if ($db->error) {
            try {
                throw new Exception("MySQL error $db->error <br> Query:<br> " , $db->errno);
            } catch(Exception $e) {
                $resultado .= "Error no. ".$e-> getCode(). "-" .$e->getMessage() . "<br>";
                $resultado .= nl2br($e->getTraceAsString());
    
                $alerta = '<b>Error!</b> '.$resultado;
                $arr = array('codigo' => 0, 'alerta' => $alerta.'---'.$insert);
            }
        }else{
            $arr = array('codigo' => 1, 'alerta' => 'Se Actualizo correctamente la actividad.');
        }


    }
}

echo json_encode($arr);          


?>