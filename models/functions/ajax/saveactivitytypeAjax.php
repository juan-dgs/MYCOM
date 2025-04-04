<?php
$db = new Conexion();

$id = $db->real_escape_string($_POST['id']);
$descripcion = $db->real_escape_string($_POST['descripcion']);

$update = "UPDATE act_c_tipos SET descripcion='$descripcion' WHERE id='$id';";

$db->query($update);
$resultado = "";

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
    $code = 1;
    $arr = array('codigo' => $code, 'alerta' => '<b>Completado!</b> Tipo de actividad actualizado correctamente.');
}

echo json_encode($arr);
?>