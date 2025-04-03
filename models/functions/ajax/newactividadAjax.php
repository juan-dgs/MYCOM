<?php 
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');


$tipo = $db->real_escape_string($_POST['tipo'] ?? '');
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

$folio = 0;

$dt_tipoU=findtablaq("SELECT 1 as id, pre FROM act_c_tipos where codigo ='$tipo' LIMIT 1;","id");

if(empty($dt_tipoU)) {
    $code = 0;
    $alerta= "<b>Error!</b>Tipo de Actividad no Valida. ". $tipo  .". ";
    $arr = array('codigo' => $code, 'alerta' => $alerta);
}else{
    $dt_Ufolio=findtablaq("SELECT 1 as id,folio FROM actividades where folio like '".date("y").$dt_tipoU[1]["pre"].date("m")."%' ORDER BY folio DESC LIMIT 1;","id");

    if(empty($dt_Ufolio)) {
        $folio = date("y").$dt_tipoU[1]["pre"].date("m")."0001";
    }else{
        $folio =date("y").$dt_tipoU[1]["pre"].date("m").str_pad(intval(substr($dt_Ufolio[1]["folio"], -4))+1,4, "0", STR_PAD_LEFT);
    }

    $insert ="INSERT INTO actividades (folio, c_tipo_act, id_cliente, c_prioridad, c_clasifica_act, descripcion, comentario, notas, dispositivo, id_usuario_resp, 
                                        f_plan_i, f_plan_f, fh_captura, id_usuario_captura, fh_finaliza, id_usuario_finaliza, c_estatus) 
                                        VALUES 
                                    ('$folio', '$tipo', '$cliente', '$prioridad', '$clasificacion', '$descripcion', '$comentarios', '$notas', '$dispositivo', $u_responsable,
                                     $fi, $ff, now(), '".$_SESSION['user_id']."', NULL, NULL, 'A');";
    if(is_array($u_involucrados)){
        if(count($u_involucrados)>0){
            foreach ($u_involucrados as $ui) {
                $insert.= "INSERT INTO act_r_involucrados (folio, id_usuario) VALUES 
                                                        ('$folio', '$ui');";
            }
        }
    }
    
    

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
        $arr = array('codigo' => 1, 'alerta' => 'Se Genero Actividad Correctamente.');
    }
}




echo json_encode($arr);


?>