<?php
// Configuración inicial recomendada
header('Content-Type: application/json; charset=utf-8');
//ini_set('display_errors', 0); // Desactivar en producción
//error_reporting(E_ALL);       // Reportar todos los errores

// Conexión a la base de datos
//$db = new Conexion();

// Verificar si se recibieron datos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['horario'])) {
    http_response_code(400);
    echo json_encode([
        'codigo' => 0,
        'alerta' => 'Método no permitido o datos faltantes'
    ]);
    exit;
}

// Decodificar el JSON recibido
$horario = json_decode($_POST['horario'], true);

// Validar que los datos recibidos son correctos
if (json_last_error() !== JSON_ERROR_NONE || empty($horario)) {
    http_response_code(400);
    echo json_encode([
        'codigo' => 0,
        'alerta' => 'Datos de horario no recibidos correctamente: ' . json_last_error_msg()
    ]);
    exit;
}

// Validar campos requeridos
$requiredFields = ['dia_semana', 'hora_inicio', 'hora_fin', 'es_laboral', 'hr_comida'];
foreach ($requiredFields as $field) {
    if (!isset($horario[$field])) {
        http_response_code(400);
        echo json_encode([
            'codigo' => 0,
            'alerta' => "Campo requerido faltante: $field"
        ]);
        exit;
    }
}

$db = new Conexion(); // Asegúrate de que la conexión a la base de datos esté configurada correctamente
try {
    // Preparar datos para la consulta
    $dia_semana = $db->real_escape_string($horario['dia_semana']);
    $hora_inicio = $db->real_escape_string($horario['hora_inicio']);
    $hora_fin = $db->real_escape_string($horario['hora_fin']);
    $es_laboral = intval($horario['es_laboral']);
    $hr_comida = intval($horario['hr_comida']);
    $dia_semana_n = array_search($dia_semana,$_DIASSEM);
   


    // Verificar si ya existe un registro para este día
    $check_query = "SELECT dia_semana FROM core_horarios_laborales WHERE dia_semana = '$dia_semana_n';";
    $result = $db->query($check_query);
   
   
    if ($result && $result->num_rows > 0) {
        // Actualizar registro existente
        $query = "UPDATE core_horarios_laborales SET 
                 hora_inicio = '$hora_inicio',
                 hora_fin = '$hora_fin',
                 es_laboral = $es_laboral,
                 hr_comida = $hr_comida
                 WHERE dia_semana = '$dia_semana_n';";
            
           

    } else{
        $response = [
            'codigo' => 0,
            'alerta' => "Error,no se encontro el dia de la semana $dia_semana ($dia_semana_n)"
        ];

    }

    // Ejecutar la consulta
    if ($db->query($query)) {
        $response = [
            'codigo' => 1,
            'alerta' => "Horario de $dia_semana actualizado correctamente",
            'dia_actualizado' => $dia_semana
        ];
    } else {
        $response = [
            'codigo' => 0,
            'alerta' => "Error al guardar horario para $dia_semana: " . $db->error.$query
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'codigo' => 0,
        'alerta' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}


?>