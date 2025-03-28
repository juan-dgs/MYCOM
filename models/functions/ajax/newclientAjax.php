<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$rfc = $db->real_escape_string($_POST['rfc'] ?? '');
$alias = $db->real_escape_string($_POST['alias'] ?? '');
$razon_social = $db->real_escape_string($_POST['razon_social'] ?? '');
$domicilio = $db->real_escape_string($_POST['domicilio'] ?? '');
$contacto = $db->real_escape_string($_POST['contacto'] ?? '');
$correo = $db->real_escape_string($_POST['correo'] ?? '');
$telefono = $db->real_escape_string($_POST['telefono'] ?? '');

// Validar campos obligatorios
if (empty($alias)) {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El alias es obligatorio.');
} elseif (empty($razon_social)) {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> La razón social es obligatoria.');
} elseif (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El formato del correo no es válido.');
} else {
    // Validar RFC si se proporcionó
    if (!empty($rfc) && !preg_match('/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
        $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> El RFC no tiene un formato válido.');
    } else {
        // Verificar si el alias ya existe
        $existe = findtablaq("SELECT id FROM act_c_clientes WHERE alias = '$alias' AND activo = 1 LIMIT 1", "id");
        
        if ($existe) {
            $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> Ya existe un cliente con ese alias.');
        } else {
            $q = "INSERT INTO act_c_clientes 
                  (rfc, alias, razon_social, domicilio, contacto, correo, telefono, activo, fh_registro)
                  VALUES 
                  ('$rfc', '$alias', '$razon_social', '$domicilio', '$contacto', '$correo', '$telefono', 1, NOW())";

            if ($db->query($q)) {
                $arr = array('codigo' => 1, 'alerta' => 'Cliente creado correctamente.');
            } else {
                $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> ' . $db->error);
            }
        }
    }
}

echo json_encode($arr);
?>