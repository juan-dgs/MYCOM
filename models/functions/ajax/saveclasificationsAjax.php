<?php
// saveclasificationsAjax.php

$db = new Conexion();
header('Content-Type: application/json');

$arr = array('codigo' => 0, 'alerta' => 'Error desconocido');

try {
    // Sanitizar inputs
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $codigo = $db->real_escape_string($_POST['codigo'] ?? '');
    $descripcion = $db->real_escape_string($_POST['descripcion'] ?? '');

    // Validar campos obligatorios
    if(empty($codigo)) {
        throw new Exception('El campo código es obligatorio');
    }
    
    if(empty($descripcion)) {
        throw new Exception('El campo descripción es obligatorio');
    }

    // Construir consulta UPDATE o INSERT
    if($id > 0) {
        // Actualización de clasificación existente
        $q = "UPDATE act_c_clasificacion SET 
              codigo = '$codigo',
              descripcion = '$descripcion'
              WHERE id = $id";
    } else {
        // Inserción de nueva clasificación
        $q = "INSERT INTO act_c_clasificacion 
              (codigo, descripcion, fh_registro, activo) 
              VALUES 
              ('$codigo', '$descripcion', NOW(), 1)";
    }

    if($db->query($q)) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => $id > 0 ? 'Clasificación actualizada correctamente' : 'Clasificación registrada correctamente'
        );
    } else {
        throw new Exception("Error en la base de datos: " . $db->error);
    }
} catch(Exception $e) {
    $arr = array('codigo' => 0, 'alerta' => $e->getMessage());
}

echo json_encode($arr, JSON_UNESCAPED_UNICODE);
exit;
?>