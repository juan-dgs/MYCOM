<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$fecha = $db->real_escape_string($_POST['fecha']);

// Verificar si existe el feriado
$sql_check = "SELECT id FROM core_feriados WHERE fecha = '$fecha' AND activo = 1 LIMIT 1";
$result_check = $db->query($sql_check);

if($result_check && $result_check->num_rows > 0) {
    $row = $result_check->fetch_assoc();
    $id = $row['id'];
    
    // Proceder con la desactivación usando el ID
    $q = "UPDATE core_feriados SET activo = 0 WHERE id = '$id'";
    
    if($db->query($q)) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => 'Día feriado eliminado correctamente.'
        );
    } else {
        $arr = array(
            'codigo' => 0, 
            'alerta' => '<b>Error!</b> No se pudo eliminar el día feriado: '.$db->error
        );
    }
} else {
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> El día feriado que desea eliminar no existe o ya fue eliminado.'
    );
}

echo json_encode($arr);
?>