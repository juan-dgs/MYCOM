<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

// Sanitizar datos del POST
$rfc = $db->real_escape_string($_POST['rfc']);
$alias = $db->real_escape_string($_POST['alias']);
$razon_social = $db->real_escape_string($_POST['razon_social']);
$domicilio = $db->real_escape_string($_POST['domicilio']);
$contacto = $db->real_escape_string($_POST['contacto']);
$correo = $db->real_escape_string($_POST['correo']);
$telefono = $db->real_escape_string($_POST['telefono'] ?? '');

// Validar campos obligatorios
if(empty($alias)) {
    $arr = array('codigo' => 0, 'alerta' => 'el campo alias es obligatorio');
    echo json_encode($arr);
    exit;
}

if(empty($razon_social)) {
    $arr = array('codigo' => 0, 'alerta' => 'el campo razón social es obligatorio');
    echo json_encode($arr);
    exit;
}

// Construir condiciones para validar duplicados
$where_conditions = array();

if(!empty($rfc)) {
    $where_conditions[] = "rfc = '$rfc'";
}

if(!empty($correo)) {
    $where_conditions[] = "correo = '$correo'";
}

// Siempre validar duplicado de razón social
$where_conditions[] = "razon_social = '$razon_social'";

$where = implode(' OR ', $where_conditions);

// Buscar posibles duplicados
$_valcliente = findtablaq("SELECT rfc, razon_social, correo FROM act_c_clientes WHERE $where LIMIT 1;", "rfc");

if(empty($_valcliente)) {
    // Construir consulta de inserción
    $q = "INSERT INTO act_c_clientes (
        rfc, alias, razon_social, domicilio, contacto, correo, telefono, fh_registro, fh_inactivo
    ) VALUES (
        ".(!empty($rfc) ? "'$rfc'" : "NULL").", 
        '$alias', 
        '$razon_social', 
        ".(!empty($domicilio) ? "'$domicilio'" : "NULL").", 
        ".(!empty($contacto) ? "'$contacto'" : "NULL").", 
        ".(!empty($correo) ? "'$correo'" : "NULL").", 
        ".(!empty($telefono) ? "'$telefono'" : "NULL").", 
        NOW(), 
        NULL
    )";

    // Ejecutar consulta
    if($db->query($q)) {
        $arr = array('codigo' => 1, 'alerta' => 'cliente creado correctamente.');
    } else {
        $arr = array('codigo' => 0, 'alerta' => '<b>error!</b> ' . $db->error);
    }
} else {
    $alerta = "";
    
    // Verificar qué campo coincide
    if(!empty($rfc) && isset($_valcliente[$rfc])) {
        $alerta = "<b>error!</b> ya existe un cliente con el rfc: $rfc.";
    }
    
    if(!empty($correo) && isset($_valcliente[$correo])) {
        $alerta .= ($alerta ? "<br>" : "") . "<b>error!</b> ya existe un cliente con el correo: $correo.";
    }
    
    // Validar razón social duplicada
    $q_razon = "SELECT 1 FROM act_c_clientes WHERE razon_social = '$razon_social' LIMIT 1";
    $result = $db->query($q_razon);
    if($result && $result->num_rows > 0) {
        $alerta .= ($alerta ? "<br>" : "") . "<b>error!</b> ya existe un cliente con la misma razón social.";
    }

    $arr = array('codigo' => 0, 'alerta' => $alerta);
}

echo json_encode($arr);
?>