<?php
$db = new Conexion();

// Inicializar array de respuesta
$arr = array('codigo' => 0, 'alerta' => 'Error desconocido');

try {
    // Sanitizar inputs y establecer valores por defecto para opcionales
    $rfc = $db->real_escape_string($_POST['rfc'] ?? '');
    $alias = $db->real_escape_string($_POST['alias'] ?? '');
    $razon_social = $db->real_escape_string($_POST['razon_social'] ?? '');
    $domicilio = $db->real_escape_string($_POST['domicilio'] ?? '');
    $contacto = $db->real_escape_string($_POST['contacto'] ?? '');
    $correo = $db->real_escape_string($_POST['correo'] ?? '');
    $telefono = $db->real_escape_string($_POST['telefono'] ?? '');

    // Validar campos obligatorios
    if(empty($alias)) {
        $arr = array('codigo' => 0, 'alerta' => 'El campo alias es obligatorio');
        echo json_encode($arr);
        exit;
    }
    
    if(empty($razon_social)) {
        $arr = array('codigo' => 0, 'alerta' => 'El campo razón social es obligatorio');
        echo json_encode($arr);
        exit;
    }

    // Verificar duplicados SOLO si se proporcionaron RFC o correo
    $whereConditions = [];
    if(!empty($rfc)) $whereConditions[] = "rfc = '$rfc'";
    if(!empty($correo)) $whereConditions[] = "correo = '$correo'";
    
    // Solo verificar duplicados si hay condiciones
    if(count($whereConditions) > 0) {
        $whereClause = "WHERE (" . implode(" OR ", $whereConditions) . ") AND activo = 1";
        $_valcliente = findtablaq("SELECT id, rfc, correo FROM act_c_clientes $whereClause LIMIT 1", "id");

        if(!empty($_valcliente)) {
            // Verificar qué campo está duplicado
            $alerta = "";
            if(!empty($rfc) && isset($_valcliente[1]['rfc']) && strtolower($rfc) == strtolower($_valcliente[1]['rfc'])) {
                $alerta = "<b>Error!</b> Ya existe un cliente con el RFC: $rfc";
            }
            if(!empty($correo) && isset($_valcliente[1]['correo']) && strtolower($correo) == strtolower($_valcliente[1]['correo'])) {
                $alerta .= $alerta ? "<br>" : "";
                $alerta .= "<b>Error!</b> Ya existe un cliente con el correo: $correo";
            }
            
            $arr = array('codigo' => 0, 'alerta' => $alerta ?: 'Error de duplicación no especificado');
            echo json_encode($arr);
            exit;
        }
    }

    // Construir consulta INSERT con campos opcionales
    $columns = ['alias', 'razon_social']; // Campos obligatorios
    $values = ["'$alias'", "'$razon_social'"];

    // Campos opcionales (solo si tienen valor)
    if(!empty($rfc)) {
        $columns[] = 'rfc';
        $values[] = "'$rfc'";
    }
    
    if(!empty($domicilio)) {
        $columns[] = 'domicilio';
        $values[] = "'$domicilio'";
    }
    
    if(!empty($contacto)) {
        $columns[] = 'contacto';
        $values[] = "'$contacto'";
    }
    
    if(!empty($correo)) {
        $columns[] = 'correo';
        $values[] = "'$correo'";
    }
    
    if(!empty($telefono)) {
        $columns[] = 'telefono';
        $values[] = "'$telefono'";
    }
    
    // Campos fijos
    $columns[] = 'fh_registro';
    $values[] = "NOW()";
    
    $columns[] = 'activo';
    $values[] = "1";

    // Construir y ejecutar query
    $q = "INSERT INTO act_c_clientes (" . implode(", ", $columns) . ") 
          VALUES (" . implode(", ", $values) . ")";

    if($db->query($q)) {
        $arr = array('codigo' => 1, 'alerta' => 'Cliente registrado correctamente');
    } else {
        throw new Exception("Error al registrar cliente: " . $db->error);
    }

} catch(Exception $e) {
    $arr = array('codigo' => 0, 'alerta' => $e->getMessage());
}

// Establecer cabecera y devolver respuesta JSON
header('Content-Type: application/json');
echo json_encode($arr);
?>