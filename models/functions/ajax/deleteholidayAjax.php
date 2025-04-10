<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$fecha = $db->real_escape_string($_POST['fecha']);

// Verificar si existe el feriado
$dt_valholiday = findtablaq("SELECT 1 as id, fecha FROM core_feriados WHERE fecha = '$fecha' AND activo = 1 LIMIT 1;", "id");

if($dt_valholiday != false) {
    // Proceder directamente con la desactivación
    $q = "UPDATE core_feriados SET activo = 0 WHERE fecha = '$fecha'";
    
    if($db->query($q)) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => 'Día feriado eliminado correctamente.'
        );
    } else {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> $q", $db->errno);
        } catch(Exception $e) {
            $resultado = "Error no. ".$e->getCode()." - ".$e->getMessage()."<br>";
            $resultado .= nl2br($e->getTraceAsString());
            $arr = array(
                'codigo' => 0, 
                'alerta' => '<b>Error!</b> '.$resultado
            );
        }
    }
} else {
    // El feriado no existe o ya está desactivado
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> El día feriado que desea eliminar no existe o ya fue eliminado.'
    );
}

echo json_encode($arr);
?>