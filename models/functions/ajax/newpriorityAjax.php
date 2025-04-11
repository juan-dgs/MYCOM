<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
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

$valPriority = findtablaq("SELECT 1 as id,codigo,descripcion FROM act_c_prioridades WHERE codigo='$codigo' OR descripcion='$descripcion' LIMIT 1;", "id");

if(empty($valPriority)) {
    $q = "INSERT INTO act_c_prioridades (codigo, descripcion, color_hex, hr_min, hr_max, icono, activo, fh_registro) 
          VALUES ('$codigo', '$descripcion', '$color_hex', '$hr_min', '$hr_max', '$icono', 1, NOW())";

    if($db->query($q)) {
        $arr = array('codigo' => 1, 'alerta' => 'Prioridad registrada correctamente');
    } else {
        $arr = array('codigo' => 0, 'alerta' => 'Error al registrar: '.$db->error);
    }
} else {
    $alerta = '';
    if(is_array($valPriority)){
        if($valPriority[1]['codigo'] == $codigo) {
            $alerta .= 'Ya existe una prioridad con este código. ';
        }
        if($valPriority[1]['descripcion'] == $descripcion) {    
            $alerta .= 'Ya existe una prioridad con esta descripción.';
        }
    }
   
    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>