<?php
// Asegurarnos de que no hay output antes de las cabeceras
ob_start();

// Configurar cabeceras para JSON primero
header('Content-Type: application/json');

// Incluir la conexión a la base de datos - VERIFICA QUE ESTA RUTA ES CORRECTA
require_once 'conexion.php'; // Cambia esto por la ruta correcta a tu archivo de conexión

// Respuesta inicial estandarizada
$response = [
    'success' => false,
    'message' => 'Error inicial',
    'codigo' => 0,
    'alerta' => 'Error desconocido'
];

try {
    // Verificar que es una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar que todos los campos requeridos están presentes
    $required = ['id', 'fecha_original', 'fecha', 'nombre'];
    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            throw new Exception("Campo requerido faltante: $field");
        }
    }

    $db = new Conexion();

    // Sanitizar entradas
    $id = (int)$_POST['id'];
    $fecha_original = $db->real_escape_string($_POST['fecha_original']);
    $fecha = $db->real_escape_string($_POST['fecha']);
    $nombre = $db->real_escape_string($_POST['nombre']);
    $es_recurrente = isset($_POST['es_recurrente']) ? 1 : 0;

    // Validar formato de fecha
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        throw new Exception('Formato de fecha inválido. Use YYYY-MM-DD');
    }

    // Verificar existencia del registro
    $check_sql = "SELECT id FROM core_feriados WHERE id = ? AND activo = 1";
    $stmt = $db->prepare($check_sql);
    if (!$stmt) {
        throw new Exception('Error al preparar consulta: ' . $db->error);
    }
    
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception('Error al verificar registro: ' . $stmt->error);
    }

    if (!$stmt->get_result()->num_rows) {
        throw new Exception('Registro no encontrado o inactivo');
    }

    // Verificar duplicados (solo si cambió la fecha)
    if ($fecha !== $fecha_original) {
        $dup_sql = "SELECT id FROM core_feriados WHERE fecha = ? AND id != ? AND activo = 1";
        $stmt = $db->prepare($dup_sql);
        $stmt->bind_param('si', $fecha, $id);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows) {
            throw new Exception('Ya existe un feriado para esta fecha');
        }
    }

    // Actualizar registro
    $update_sql = "UPDATE core_feriados SET 
                  fecha = ?, 
                  nombre = ?, 
                  es_recurrente = ?,
                  fh_actualizacion = NOW()
                  WHERE id = ?";
    
    $stmt = $db->prepare($update_sql);
    $stmt->bind_param('ssii', $fecha, $nombre, $es_recurrente, $id);
    
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Actualización exitosa',
            'codigo' => 1,
            'alerta' => 'Día feriado actualizado correctamente'
        ];
    } else {
        throw new Exception('Error al actualizar: ' . $stmt->error);
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'codigo' => 0,
        'alerta' => '<b>Error!</b> ' . $e->getMessage()
    ];
} finally {
    // Limpiar buffer y enviar JSON
    ob_end_clean();
    echo json_encode($response);
    exit;
}