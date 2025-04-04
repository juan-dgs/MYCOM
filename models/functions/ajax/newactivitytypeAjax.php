<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$codigo = $db->real_escape_string($_POST['codigo']);
$descripcion = $db->real_escape_string($_POST['descripcion']);
$pre = $db->real_escape_string($_POST['pre']);

// Validación back-end para el código (4 caracteres)
if(strlen($codigo) != 4) {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El código debe tener exactamente 4 caracteres');
    echo json_encode($arr);
    exit;
}

// Validación back-end para el prefijo (1 letra)
if(strlen($pre) != 1 || !preg_match('/^[a-zA-Z]$/', $pre)) {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El prefijo debe ser exactamente 1 letra');
    echo json_encode($arr);
    exit;
}

$_valtipo = findtablaq("SELECT 1 as id, codigo, descripcion, pre FROM act_c_tipos WHERE codigo='$codigo' OR descripcion='$descripcion' OR pre='$pre' LIMIT 1;", "id");

if(empty($_valtipo)) {
    $q = "INSERT INTO act_c_tipos (codigo, descripcion, pre, activo, fh_registro)
          VALUES ('$codigo', '$descripcion', '$pre', 1, NOW());";

    $db->query($q);

    if($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
        } catch(Exception $e) {
            $resultado .= "Error no. ".$e->getCode()."-".$e->getMessage()."<br>";
            $resultado .= nl2br($e->getTraceAsString());
            $alerta = '<b>Error!</b> '.$resultado;
            $arr = array('codigo' => 0, 'alerta' => $alerta);
        }
    } else {
        $arr = array('codigo' => 1, 'alerta' => 'Tipo de actividad creado correctamente.');
    }
} else {
    $code = 0;
    $alerta = "";
    
    if(strtolower($codigo) == strtolower($_valtipo[1]["codigo"])) {
        $alerta = "<b>Error!</b> Ya existe un código que coincide con ".$codigo;
    } else if(strtolower($descripcion) == strtolower($_valtipo[1]["descripcion"])) {
        $alerta = "<b>Error!</b> Ya existe una descripción que coincide con ".$descripcion;
    } else if(strtolower($pre) == strtolower($_valtipo[1]["pre"])) {
        $alerta = "<b>Error!</b> Ya existe un prefijo que coincide con ".$pre;
    }
    
    $arr = array('codigo' => $code, 'alerta' => $alerta);
}

echo json_encode($arr);
?>