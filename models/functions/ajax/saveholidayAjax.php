<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$fecha_original = $db->real_escape_string($_POST['fecha_original']);
$fecha = $db->real_escape_string($_POST['fecha']);
$nombre = $db->real_escape_string($_POST['nombre']);
$es_recurrente = isset($_POST['es_recurrente']) ? 1 : 0;

// Función auxiliar para verificar existencia
function existeRegistro($db, $tabla, $campo, $valor, $excluirFecha = null) {
    $sql = "SELECT COUNT(*) as total FROM $tabla WHERE $campo = '$valor' AND activo = 1";
    if($excluirFecha) {
        $sql .= " AND fecha != '$excluirFecha'";
    }
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] > 0;
}

// Validar si la fecha ha cambiado y si la nueva fecha ya existe
if($fecha != $fecha_original) {
    $sql = "SELECT COUNT(*) as total FROM core_feriados WHERE fecha = '$fecha' AND activo = 1";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    
    if($row['total'] > 0) {
        $arr = array(
            'codigo' => 0, 
            'alerta' => '<b>Error!</b> Ya existe un feriado registrado para la fecha '.date("d/m/Y", strtotime($fecha))
        );
        echo json_encode($arr);
        exit;
    }
}

// Validar si el nombre ya existe para otra fecha
$sql = "SELECT COUNT(*) as total FROM core_feriados WHERE nombre = '$nombre' AND fecha != '$fecha_original' AND activo = 1";
$result = $db->query($sql);
$row = $result->fetch_assoc();

if($row['total'] > 0) {
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> Ya existe un feriado con el nombre "'.$nombre.'" para otra fecha'
    );
    echo json_encode($arr);
    exit;
}

// Construir y ejecutar la consulta SQL
$q = "UPDATE core_feriados SET 
      fecha = '$fecha', 
      nombre = '$nombre', 
      es_recurrente = $es_recurrente 
      WHERE fecha = '$fecha_original' AND activo = 1";

if($db->query($q)) {
    if($db->affected_rows > 0) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => 'Día feriado actualizado correctamente'
        );
    } else {
        $arr = array(
            'codigo' => 0, 
            'alerta' => 'No se realizaron cambios o el feriado no existe'
        );
    }
} else {
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> Error en la base de datos: '.$db->error
    );
}

echo json_encode($arr);
?>