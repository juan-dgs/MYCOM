<?php
$db = new Conexion();

$id = $db->real_escape_string($_GET['id'] ?? '');

$sql = "SELECT 
        id, rfc, alias, razon_social, domicilio, contacto, correo, telefono
        FROM act_c_clientes 
        WHERE id = '$id' AND activo = 1
        LIMIT 1";

$cliente = findtablaq($sql, "id");

if ($cliente != false) {
    echo json_encode($cliente[$id]);
} else {
    echo json_encode(array('error' => 'Cliente no encontrado o inactivo'));
}
?>