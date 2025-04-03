<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$id = $db->real_escape_string($_POST['id']);

// Verificar si el cliente existe y está activo
$dt_valcliente = findtablaq("SELECT id FROM act_c_clientes WHERE id = $id AND activo = 1 LIMIT 1", "id");

if ($dt_valcliente != false) {
    // Consulta para desactivar el cliente
    $q = "UPDATE act_c_clientes SET activo = '0', fh_inactivo = NOW() WHERE id = $id";
    
    $db->query($q);

    if ($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
        } catch(Exception $e) {
            $resultado = "Error no. ".$e->getCode()."-".$e->getMessage()."<br>";
            $resultado .= nl2br($e->getTraceAsString());

            $alerta = '<b>Error!</b> '.$resultado;
            $arr = array('codigo' => 0, 'alerta' => $alerta);
        }
    } else {
        $arr = array('codigo' => 1, 'alerta' => 'Cliente desactivado correctamente.');
    }
} else {
    // El cliente no existe o ya está inactivo
    $alerta = "<b>Error!</b> El cliente que desea desactivar no existe o ya está inactivo (ID: $id)";
    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>