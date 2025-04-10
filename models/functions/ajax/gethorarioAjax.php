<?php
// Configuración de errores (mejor para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$db = new Conexion();

header('Content-Type: application/json; charset=utf-8');

try {
    // Ordenamos los días según el requerimiento (Domingo primero)
    $query = "SELECT dia_semana, hora_inicio, hora_fin, es_laboral, hr_comida 
              FROM core_horarios_laborales
              ORDER BY FIELD(dia_semana, 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado')";
    
    $result = $db->query($query);

    if($result && $result->num_rows > 0) {
        $horarios = array();
        while($row = $result->fetch_assoc()) {
            // Asegurar formato consistente de horas
            $row['hora_inicio'] = !empty($row['hora_inicio']) ? substr($row['hora_inicio'], 0, 5) : null;
            $row['hora_fin'] = !empty($row['hora_fin']) ? substr($row['hora_fin'], 0, 5) : null;
            $horarios[] = $row;
        }
        
        echo json_encode(array(
            'codigo' => 1,
            'data' => $horarios,
            'alerta' => 'Horarios cargados correctamente'
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Si no hay registros, devolver estructura vacía en el orden correcto
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $horarios = array();
    foreach($dias as $dia) {
        $horarios[] = array(
            'dia_semana' => $dia,
            'hora_inicio' => ($dia != 'Sábado' && $dia != 'Domingo') ? '09:00' : null,
            'hora_fin' => ($dia != 'Sábado' && $dia != 'Domingo') ? '18:00' : null,
            'es_laboral' => ($dia != 'Sábado' && $dia != 'Domingo') ? 1 : 0,
            'hr_comida' => ($dia != 'Sábado' && $dia != 'Domingo') ? 60 : 0
        );
    }
    
    echo json_encode(array(
        'codigo' => 1,
        'data' => $horarios,
        'alerta' => 'Horarios predeterminados cargados'
    ), JSON_UNESCAPED_UNICODE);
    exit;

} catch(Exception $e) {
    // Log del error (recomendado)
    error_log("Error en getHorarioAjax.php: " . $e->getMessage());
    
    echo json_encode(array(
        'codigo' => 0,
        'alerta' => 'Error al cargar los horarios. Por favor, intente nuevamente.'
        // No mostrar el mensaje de error real en producción
    ), JSON_UNESCAPED_UNICODE);
    exit;
}
?>