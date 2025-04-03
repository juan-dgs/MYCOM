<?php
// deleteclasificationAjax.php

session_start();
require_once 'Conexion.php';

header('Content-Type: application/json');

$response = ['codigo' => 0, 'alerta' => 'Error desconocido'];

try {
    if (!isset($_POST['id'])) {
        throw new Exception('No se recibió ID de clasificación');
    }
    
    $id = intval($_POST['id']);
    if ($id <= 0) {
        throw new Exception('ID de clasificación no válido');
    }

    $sql = "UPDATE act_c_clasificacion SET 
            activo = 0, 
            fh_inactivo = NOW()";
            
    ", u_inactivo = ".(isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 'NULL');
    
    $sql .= " WHERE id = $id AND activo = 1";

    $db = new Conexion();
    if ($db->query($sql)) {
        if ($db->affected_rows > 0) {
            $response = [
                'codigo' => 1, 
                'alerta' => 'Clasificación desactivada correctamente'
            ];
        } else {
            // Verificar si existe pero ya está inactivo
            $check = $db->query("SELECT id FROM act_c_clasificacion WHERE id = $id");
            if ($check->num_rows > 0) {
                $response['alerta'] = 'La clasificación ya estaba inactiva';
            } else {
                $response['alerta'] = 'La clasificación no existe';
            }
        }
    } else {
        throw new Exception("Error al desactivar: " . $db->error);
    }

} catch(Exception $e) {
    $response['alerta'] = $e->getMessage();
    error_log("Error en deleteclasificationAjax: " . $e->getMessage());
}

// 8. Enviar respuesta
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>