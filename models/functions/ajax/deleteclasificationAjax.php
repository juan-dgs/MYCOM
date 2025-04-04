<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$id = $db->real_escape_string($_POST['id']);

// Verificar si la clasificación existe y está activa
$dt_valclasif = findtablaq("SELECT 1 as id, codigo FROM act_c_clasificacion 
                            WHERE id='$id' AND activo=1 LIMIT 1", "id");

if($dt_valclasif != false) {
    // Verificar si la clasificación está siendo usada
    // (Aquí deberías agregar tu lógica de validación si es necesario)
    
    $q = "UPDATE act_c_clasificacion SET activo=0, fh_inactivo=NOW() WHERE id='$id'";
    
    $db->query($q);
    
    if($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
        } catch(Exception $e) {
            $resultado = "Error no. ".$e->getCode(). "-" .$e->getMessage() . "<br>";
            $resultado .= nl2br($e->getTraceAsString());
            $alerta = '<b>Error!</b> '.$resultado;
            $arr = array('codigo' => 0, 'alerta' => $alerta);
        }
    } else {
        $arr = array('codigo' => 1, 'alerta' => 'Clasificación eliminada correctamente.');
    }
} else {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> La clasificación no existe o ya fue eliminada.');
}

echo json_encode($arr);
?>