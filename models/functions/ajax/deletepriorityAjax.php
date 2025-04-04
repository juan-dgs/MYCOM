<?php
$db = new Conexion();

// Inicializar array de respuesta
$arr = ['codigo' => 0, 'alerta' => 'Error desconocido'];

// Verificar si se recibió el ID
if(!isset($_POST['id']) || empty($_POST['id'])) {
    $arr['alerta'] = '<b>Error!</b> No se recibió el ID de la prioridad';
    echo json_encode($arr);
    exit;
}

$id = $db->real_escape_string($_POST['id']);

// Consulta para verificar si la prioridad existe y está activa
$query = "SELECT codigo FROM act_c_prioridades WHERE id = '$id' AND activo = 1 LIMIT 1";
$result = $db->query($query);

if(!$result) {
    $arr['alerta'] = '<b>Error!</b> Problema al consultar la prioridad: '.$db->error;
    echo json_encode($arr);
    exit;
}

if($result->num_rows === 0) {
    $arr['alerta'] = '<b>Error!</b> La prioridad no existe o ya fue eliminada';
    echo json_encode($arr);
    exit;
}

// Obtener el código de la prioridad
$priority = $result->fetch_assoc();
$codigo = $priority['codigo'];

// Verificar si hay actividades usando esta prioridad
$queryActivities = "SELECT id FROM actividades WHERE c_prioridad = '$codigo' AND c_estatus = 1 LIMIT 1";
$resultActivities = $db->query($queryActivities);

if(!$resultActivities) {
    $arr['alerta'] = '<b>Error!</b> Problema al verificar actividades: '.$db->error;
    echo json_encode($arr);
    exit;
}

if($resultActivities->num_rows > 0) {
    $arr['alerta'] = '<b>Error!</b> No se puede eliminar la prioridad porque está siendo utilizada en actividades';
    echo json_encode($arr);
    exit;
}

// Eliminar (desactivar) la prioridad
$update = "UPDATE act_c_prioridades SET activo = 0, fh_inactivo = NOW() WHERE id = '$id'";

if($db->query($update)) {
    $arr = [
        'codigo' => 1, 
        'alerta' => '<b>Éxito!</b> Prioridad eliminada correctamente.'
    ];
} else {
    $arr = [
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> No se pudo eliminar la prioridad. '.$db->error
    ];
}

echo json_encode($arr);
?>