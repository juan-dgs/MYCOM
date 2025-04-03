<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$folio = $db->real_escape_string($_POST['folio'] ?? '');

$clasificacion = $db->real_escape_string($_POST['clasificacion'] ?? '');

$prioridad = $db->real_escape_string($_POST['prioridad'] ?? '');
$cliente = $db->real_escape_string($_POST['cliente'] ?? '');

$u_responsable = $db->real_escape_string($_POST['u_responsable'] ?? '');
$u_responsable = ($u_responsable==''?'NULL':"'".$u_responsable."'");

$u_involucrados = (isset($_POST['u_involucrados'])?$_POST['u_involucrados']:'');

$descripcion = $db->real_escape_string($_POST['descripcion'] ?? '');
$comentarios = $db->real_escape_string($_POST['comentarios'] ?? '');
$notas = $db->real_escape_string($_POST['notas'] ?? '');

$fi = $db->real_escape_string($_POST['fi'] ?? '');
$fi = ($fi==''?'NULL':"'".$fi."'");

$ff = $db->real_escape_string($_POST['ff'] ?? '');
$ff = ($ff==''?'NULL':"'".$ff."'");

$dispositivo = $db->real_escape_string($_POST['dispositivo'] ?? '');



    $update = "UPDATE actividades SET id_cliente='$cliente',c_prioridad='$prioridad',c_clasifica_act='$clasificacion',descripcion='$descripcion',comentario='$comentarios',notas='$notas',dispositivo='$dispositivo',id_usuario_resp=$u_responsable,f_plan_i=$fi,f_plan_f=$ff WHERE folio='$folio';";

    $update .="DELETE FROM act_r_involucrados WHERE folio = '$folio';";

            if(is_array($u_involucrados)){
                if(count($u_involucrados)>0){
                    foreach ($u_involucrados as $ui) {
                        $update.= "INSERT INTO act_r_involucrados (folio, id_usuario) VALUES 
                                                                ('$folio', '$ui');";
                    }
                }
            }

            //$arr = array('codigo' => 0, 'alerta' => 'Se Genero Actividad Correctamente.'.$update);


            $db->multi_query($update);
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
          
            echo json_encode($arr);          
       
  

?>

