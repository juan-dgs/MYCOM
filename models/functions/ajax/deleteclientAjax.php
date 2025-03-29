<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$id = $db->real_escape_string($_POST['id'] ?? '');

// Obtener información del usuario actual (debes adaptar esto según tu sistema)
session_start();
$u_inactivo = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Verificar si el cliente existe y está activo
$val_cliente = findtablaq("SELECT id, alias FROM act_c_clientes WHERE id = '$id' AND activo = 1 LIMIT 1", "id");

if ($val_cliente != false) {
    $q = "UPDATE act_c_clientes SET 
          activo = 0, 
          fh_inactivo = NOW(), 
          u_inactivo = '$u_inactivo' 
          WHERE id = '$id'";
    
    if ($db->query($q)) {
        $arr = array('codigo' => 1, 'alerta' => 'Cliente "'.$val_cliente[$id]['alias'].'" desactivado correctamente.');
    } else {
        $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> ' . $db->error);
    }
} else {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El cliente no existe o ya está inactivo.');
}

echo json_encode($arr);
?>