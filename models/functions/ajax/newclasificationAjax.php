<?php
// newclasificacionAjax.php

header('Content-Type: application/json');
require_once 'Conexion.php';

$response = ['codigo' => 0, 'alerta' => 'Error desconocido'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    if (empty($codigo)) {
        throw new Exception('El campo código es obligatorio');
    }

    if (empty($descripcion)) {
        throw new Exception('El campo descripción es obligatorio');
    }

    $db = new Conexion();
    if ($db->connect_error) {
        throw new Exception('Error de conexión a la base de datos');
    }

    $codigo = $db->real_escape_string($codigo);
    $descripcion = $db->real_escape_string($descripcion);

    $check = $db->query("SELECT id FROM act_c_clasificacion WHERE codigo = '$codigo' AND activo = 1");
    if ($check && $check->num_rows > 0) {
        throw new Exception('El código de clasificación ya existe');
    }

    $sql = "INSERT INTO act_c_clasificacion 
            (codigo, descripcion, fh_registro, activo) 
            VALUES 
            ('$codigo', '$descripcion', NOW(), 1)";

    if ($db->query($sql)) {
        $response = [
            'codigo' => 1,
            'alerta' => 'Clasificación creada correctamente',
            'id' => $db->insert_id  // Opcional: devolver el ID generado
        ];
    } else {
        throw new Exception('Error al guardar: ' . $db->error);
    }

} catch (Exception $e) {
    $response['alerta'] = $e->getMessage();
    http_response_code($e->getCode() ?: 500);
}

// 9. Enviar respuesta JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>