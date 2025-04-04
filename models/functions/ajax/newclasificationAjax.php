<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$codigo = $db->real_escape_string($_POST['codigo']);
$descripcion = $db->real_escape_string($_POST['descripcion']);

// Validar si ya existe el código o descripción
$_valclasif = findtablaq("SELECT 1 as id, codigo, descripcion FROM act_c_clasificacion 
                          WHERE codigo='$codigo' OR descripcion='$descripcion' 
                          AND activo=1 LIMIT 1", "id");

if(empty($_valclasif)) {
    $q = "INSERT INTO act_c_clasificacion (codigo, descripcion, activo, fh_registro) 
          VALUES ('$codigo', '$descripcion', 1, NOW())";

    $db->query($q);

    if($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
        } catch(Exception $e) {
            $resultado = "Error no. ".$e->getCode(). "-" .$e->getMessage() . "<br>";
            $resultado .= nl2br($e->getTraceAsString());
            $alerta = '<b>Error!</b> '.$resultado;
            $arr = array('codigo' => 0, 'alerta' => $alerta);
        }
    } else {
        $arr = array('codigo' => 1, 'alerta' => 'Clasificación creada correctamente.');
    }
} else {
    $alerta = "";
    if(strtolower($codigo) == strtolower($_valclasif[1]["codigo"])) {
        $alerta = "<b>Error!</b> Ya existe una clasificación con el código: ".$codigo;
    } else if(strtolower($descripcion) == strtolower($_valclasif[1]["descripcion"])) {
        $alerta = "<b>Error!</b> Ya existe una clasificación con la descripción: ".$descripcion;
    }
    
    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>