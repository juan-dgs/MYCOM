<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$id = $db->real_escape_string($_POST['id'] ?? '');
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
        // Verificar si el alias ya existe en otro cliente
        $existe = findtablaq("SELECT id FROM act_c_clientes WHERE alias = '$alias' AND id != '$id' AND activo = 1 LIMIT 1", "id");
        
        if ($existe) {
            $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> Ya existe otro cliente con ese alias.');
        } else {
            $q = "UPDATE act_c_clientes SET
                  rfc = '$rfc',
                  alias = '$alias',
                  razon_social = '$razon_social',
                  domicilio = '$domicilio',
                  contacto = '$contacto',
                  correo = '$correo',
                  telefono = '$telefono'
                  WHERE id = '$id'";

            if ($db->query($q)) {
                $arr = array('codigo' => 1, 'alerta' => 'Cliente actualizado correctamente.');
            } else {
                $arr = array('codigo' => 0, 'alerta' => '<b>Error!</b> ' . $db->error);
            }
        }
    }
}

echo json_encode($arr);
?>