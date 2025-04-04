<?php
$db = new Conexion();

$id = $db->real_escape_string($_POST['id']);
$descripcion = $db->real_escape_string($_POST['descripcion']);

// Validar si ya existe la descripción
$_valdesc = findtablaq("SELECT 1 as id FROM act_c_clasificacion 
                        WHERE descripcion='$descripcion' AND id!='$id' AND activo=1 LIMIT 1", "id");

if(empty($_valdesc)) {
    $update = "UPDATE act_c_clasificacion SET descripcion='$descripcion' 
               WHERE id='$id' AND activo=1";
    
    $db->query($update);
    
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
        $arr = array('codigo' => 1, 'alerta' => 'Clasificación actualizada correctamente.');
    }
} else {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> Ya existe una clasificación con esa descripción.');
}

echo json_encode($arr);
?>