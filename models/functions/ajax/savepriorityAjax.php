<?php
header('Content-Type: application/json');

$db = new Conexion();

// Verificar si es solicitud AJAX
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die(json_encode(['codigo' => 0, 'alerta' => 'Acceso no permitido']));
}

// Desactivar errores HTML
ini_set('display_errors', 0);
error_reporting(0);

try {
    $id = $db->real_escape_string($_POST['id'] ?? '');
    $descripcion = $db->real_escape_string($_POST['descripcion'] ?? '');
    $color_hex = $db->real_escape_string($_POST['color_hex'] ?? '#FF0000');
    $hr_min = $db->real_escape_string($_POST['hr_min'] ?? '0');
    $hr_max = $db->real_escape_string($_POST['hr_max'] ?? '0');
    $icono = $db->real_escape_string($_POST['icono'] ?? '');

    // Validaciones
    if(empty($id) || empty($descripcion) || empty($icono)) {
        throw new Exception("Faltan campos requeridos");
    }

    // Query CORREGIDO (sin fecha de actualización y con comas correctas)
    $q = "UPDATE act_c_prioridades SET 
            descripcion = '$descripcion',
            color_hex = '$color_hex',
            hr_min = '$hr_min',
            hr_max = '$hr_max',
            icono = '$icono'
          WHERE id = '$id'";

    if(!$db->query($q)) {
        throw new Exception("Error en BD: ".$db->error);
    }

    echo json_encode([
        'codigo' => 1,
        'alerta' => '¡Prioridad actualizada correctamente!'
    ]);
    exit;

} catch(Exception $e) {
    echo json_encode([
        'codigo' => 0,
        'alerta' => 'Error: '.$e->getMessage()
    ]);
    exit;
}