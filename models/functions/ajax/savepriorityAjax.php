<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$id = $db->real_escape_string($_POST['id']);
$codigo = $db->real_escape_string($_POST['codigo']);
$descripcion = $db->real_escape_string($_POST['descripcion']);
$color_hex = $db->real_escape_string($_POST['color_hex']);
$hr_min = $db->real_escape_string($_POST['hr_min']);
$hr_max = $db->real_escape_string($_POST['hr_max']);
$icono = $db->real_escape_string($_POST['icono']);

$codigo = strtoupper($codigo);

// Validaciones
if(empty($icono)) {
    echo json_encode(['codigo' => 0, 'alerta' => 'Debe seleccionar un ícono']);
    exit;
}

$valPriority = findtablaq("SELECT 1 as id FROM act_c_prioridades WHERE (codigo='$codigo' OR descripcion='$descripcion') AND id != '$id' LIMIT 1", "id");

if(empty($valPriority)) {
    $q = "UPDATE act_c_prioridades SET 
            codigo = '$codigo',
            descripcion = '$descripcion',
            color_hex = '$color_hex',
            hr_min = '$hr_min',
            hr_max = '$hr_max',
            icono = '$icono'
          WHERE id = '$id'";

    if($db->query($q)) {
        $arr = array('codigo' => 1, 'alerta' => 'Prioridad actualizada correctamente');
    } else {
        $arr = array('codigo' => 0, 'alerta' => 'Error al actualizar: '.$db->error);
    }
} else {
    $alerta = '';
    if(findtablaq("SELECT 1 FROM act_c_prioridades WHERE codigo='$codigo' AND id != '$id' LIMIT 1", "id")) {
        $alerta .= 'Ya existe otra prioridad con este código. ';
    }
    if(findtablaq("SELECT 1 FROM act_c_prioridades WHERE descripcion='$descripcion' AND id != '$id' LIMIT 1", "id")) {
        $alerta .= 'Ya existe otra prioridad con esta descripción.';
    }
    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>