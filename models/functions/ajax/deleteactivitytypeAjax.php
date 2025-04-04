<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$id = $db->real_escape_string($_POST['id']);

$dt_valtipo = findtablaq("SELECT 1 as id, codigo FROM act_c_tipos WHERE id=$id AND activo=1 LIMIT 1;", "id");

if(($dt_valtipo != false)) {
    $dt_valactividades = findtablaq("SELECT id FROM actividades WHERE c_tipo_act = '".$dt_valtipo[1]['codigo']."' AND c_estatus = 1;", "id");
    
    if(($dt_valactividades == false)) {
        $q = "UPDATE act_c_tipos SET activo=0, fh_inactivo=NOW() WHERE id=$id;";
        $db->query($q);

        if($db->error) {
            try {
                throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
            } catch(Exception $e) {
                $resultado .= "Error no. ".$e->getCode()."-".$e->getMessage()."<br>";
                $resultado .= nl2br($e->getTraceAsString());
                $alerta = '<b>Error!</b> '.$resultado;
                $arr = array('codigo' => 0, 'alerta' => $alerta);
            }
        } else {
            $arr = array('codigo' => 1, 'alerta' => 'Tipo de actividad eliminado correctamente.');
        }
    } else {
        $alerta = "<b>Error!</b> Existen actividades asociadas a este tipo que no pueden ser eliminadas";
        $arr = array('codigo' => 0, 'alerta' => $alerta);
    }
} else {
    $alerta = "<b>Error!</b> El tipo de actividad que intenta eliminar no existe o ya fue eliminado";
    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>