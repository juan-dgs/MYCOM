<?php
$db = new Conexion();

// Configurar cabecera para respuesta JSON
header('Content-Type: application/json');

// Inicializar array de respuesta
$arr = array('codigo' => 0, 'alerta' => 'Error desconocido');

try {
    // Sanitizar input
    $id = $db->real_escape_string($_POST['id'] ?? '');
    
    // Obtener ID del usuario que realiza la acción (de la sesión)
    session_start();
    $u_inactivo = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Verificar que el cliente exista y esté activo
    $dt_valcliente = findtablaq("SELECT id_cliente FROM act_c_clientes WHERE id_cliente = '$id' AND activo = 1 LIMIT 1", "id_cliente");

    if($dt_valcliente != false) {
        // Actualizar registro (borrado lógico)
        $q = "UPDATE act_c_clientes SET 
              activo = 0, 
              fh_inactivo = NOW(), 
              u_inactivo = '$u_inactivo' 
              WHERE id_cliente = '$id'";
        
        if($db->query($q)) {
            $arr = array(
                'codigo' => 1, 
                'alerta' => 'Cliente desactivado correctamente'
            );
        } else {
            throw new Exception("Error al desactivar cliente: " . $db->error);
        }
    } else {
        $arr = array(
            'codigo' => 0, 
            'alerta' => 'El cliente no existe o ya está inactivo'
        );
    }
} catch(Exception $e) {
    $arr = array(
        'codigo' => 0, 
        'alerta' => 'Error: ' . $e->getMessage()
    );
}

echo json_encode($arr);
?>