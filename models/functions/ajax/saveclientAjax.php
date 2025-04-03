<?php
// ajax.php?mode=saveclient

$db = new Conexion();
$arr = array('codigo' => 0, 'alerta' => 'Error desconocido');

try {
    // Sanitizar inputs
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $alias = $db->real_escape_string($_POST['alias'] ?? '');
    $razon_social = $db->real_escape_string($_POST['razon_social'] ?? '');
    $rfc = $db->real_escape_string($_POST['rfc'] ?? '');
    $domicilio = $db->real_escape_string($_POST['domicilio'] ?? '');
    $contacto = $db->real_escape_string($_POST['contacto'] ?? '');
    $correo = $db->real_escape_string($_POST['correo'] ?? '');
    $telefono = $db->real_escape_string($_POST['telefono'] ?? '');

    // Validar campos obligatorios
    if(empty($alias)) {
        throw new Exception('El campo alias es obligatorio');
    }
    
    if(empty($razon_social)) {
        throw new Exception('El campo razón social es obligatorio');
    }

    // Validar formato de correo si se proporcionó
    if(!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo electrónico no es válido');
    }

    // Verificar duplicados
    if($id == 0) { // Solo para nuevos registros
        $whereConditions = [];
        $paramsToCheck = [];
        
        if(!empty($rfc)) {
            $whereConditions[] = "rfc = '$rfc'";
            $paramsToCheck['rfc'] = $rfc;
        }
        
        if(!empty($correo)) {
            $whereConditions[] = "correo = '$correo'";
            $paramsToCheck['correo'] = $correo;
        }
        
        if(!empty($whereConditions)) {
            $whereClause = "WHERE (" . implode(" OR ", $whereConditions) . ") AND activo = 1";
            $sql = "SELECT id, rfc, correo FROM act_c_clientes $whereClause LIMIT 1";
            $result = $db->query($sql);
            
            if($result && $result->num_rows > 0) {
                $existing = $result->fetch_assoc();
                $errors = [];
                
                foreach($paramsToCheck as $field => $value) {
                    if(isset($existing[$field]) && !empty($existing[$field])) {
                        $errors[] = "Ya existe un cliente con $field: " . htmlspecialchars($value);
                    }
                }
                
                if(!empty($errors)) {
                    throw new Exception(implode("<br>", $errors));
                }
            }
        }
    }

    // Construir consulta
    if($id > 0) {
        // ACTUALIZAR cliente existente
        $updates = [
            "alias = '$alias'",
            "razon_social = '$razon_social'",
            !empty($rfc) ? "rfc = '$rfc'" : "rfc = NULL",
            !empty($domicilio) ? "domicilio = '$domicilio'" : "domicilio = NULL",
            !empty($contacto) ? "contacto = '$contacto'" : "contacto = NULL",
            !empty($correo) ? "correo = '$correo'" : "correo = NULL",
            !empty($telefono) ? "telefono = '$telefono'" : "telefono = NULL"
        ];
        
        $query = "UPDATE act_c_clientes SET " . implode(", ", array_filter($updates)) . " WHERE id = $id";
    } else {
        // INSERTAR nuevo cliente
        $columns = ['alias', 'razon_social', 'fh_registro', 'activo'];
        $values = ["'$alias'", "'$razon_social'", "NOW()", "1"];
        
        if(!empty($rfc)) { $columns[] = 'rfc'; $values[] = "'$rfc'"; }
        if(!empty($domicilio)) { $columns[] = 'domicilio'; $values[] = "'$domicilio'"; }
        if(!empty($contacto)) { $columns[] = 'contacto'; $values[] = "'$contacto'"; }
        if(!empty($correo)) { $columns[] = 'correo'; $values[] = "'$correo'"; }
        if(!empty($telefono)) { $columns[] = 'telefono'; $values[] = "'$telefono'"; }
        
        $query = "INSERT INTO act_c_clientes (" . implode(", ", $columns) . ") 
                 VALUES (" . implode(", ", $values) . ")";
    }

    if($db->query($query)) {
        $arr = array(
            'codigo' => 1, 
            'alerta' => $id > 0 ? 'Cliente actualizado correctamente' : 'Cliente registrado correctamente'
        );
    } else {
        throw new Exception("Error en la base de datos: " . $db->error);
    }
} catch(Exception $e) {
    $arr = array('codigo' => 0, 'alerta' => $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($arr, JSON_UNESCAPED_UNICODE);
exit();
?>