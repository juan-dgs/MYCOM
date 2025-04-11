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

    // Primero verificar si hay cambios
    $current_data = $db->query("SELECT descripcion, color_hex, hr_min, hr_max, icono 
                              FROM act_c_prioridades 
                              WHERE id = '$id'");
    if($current_data->num_rows === 0) {
        throw new Exception("La prioridad no existe");
    }
    
    $row = $current_data->fetch_assoc();
    $has_changes = false;
    
    if($row['descripcion'] != $descripcion || 
       $row['color_hex'] != $color_hex || 
       $row['hr_min'] != $hr_min || 
       $row['hr_max'] != $hr_max || 
       $row['icono'] != $icono) {
        $has_changes = true;
    }
    
    if(!$has_changes) {
        echo json_encode([
            'codigo' => 1,
            'alerta' => 'No se detectaron cambios para guardar'
        ]);
        exit;
    }

    // Query de actualización
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
?>