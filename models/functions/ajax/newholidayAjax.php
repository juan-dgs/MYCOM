<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$fecha = $db->real_escape_string($_POST['fecha']);
$nombre = $db->real_escape_string($_POST['nombre']);
$es_recurrente = isset($_POST['es_recurrente']) ? 1 : 0;

// Verificar si ya existe un feriado con la misma fecha o nombre
$sql = "SELECT fecha, nombre FROM core_feriados 
        WHERE (fecha = '$fecha' OR nombre = '$nombre') 
        AND activo = 1 
        LIMIT 1";
$result = $db->query($sql);

if($result->num_rows == 0) {
    // No existe, proceder con la inserción
    $q = "INSERT INTO core_feriados (fecha, nombre, es_recurrente, activo, fh_registro)
          VALUES ('$fecha', '$nombre', $es_recurrente, 1, NOW())";

    if($db->query($q)) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => 'Día feriado registrado correctamente'
        );
    } else {
        // Manejo de errores de MySQL
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
    // Ya existe un registro con esa fecha o nombre
    $row = $result->fetch_assoc();
    $code = 0;
    $alerta = "";
    
    if($row['fecha'] == $fecha) {
        $alerta = "<b>Error!</b> Ya existe un feriado registrado para la fecha ".date("d/m/Y", strtotime($fecha));
    } else if(strtolower($row['nombre']) == strtolower($nombre)) {
        $alerta = "<b>Error!</b> Ya existe un feriado con el nombre '".$nombre."'";
    }

    $arr = array('codigo' => $code, 'alerta' => $alerta);
}

echo json_encode($arr);
?>