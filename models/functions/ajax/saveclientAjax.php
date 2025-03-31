<?php
$db = new Conexion();

// Configurar cabecera para respuesta JSON
header('Content-Type: application/json');

// Inicializar array de respuesta
$arr = array('codigo' => 0, 'alerta' => 'Error desconocido');

try {
    // Sanitizar inputs
    $id = $db->real_escape_string($_POST['id'] ?? '');
    $alias = $db->real_escape_string($_POST['alias'] ?? '');
    $razon_social = $db->real_escape_string($_POST['razon_social'] ?? '');
    $rfc = $db->real_escape_string($_POST['rfc'] ?? '');
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

    // Verificar duplicados (RFC o correo) solo si se proporcionan valores
    $whereConditions = [];
    if(!empty($rfc)) $whereConditions[] = "rfc = '$rfc'";
    if(!empty($correo)) $whereConditions[] = "correo = '$correo'";
    
    $whereClause = count($whereConditions) > 0 ? 
                   "WHERE (" . implode(" OR ", $whereConditions) . ") AND id != '$id' AND activo = 1" : 
                   "WHERE 1=0"; // Condición que nunca se cumple si no hay campos para verificar
    
    $_valcliente = findtablaq("SELECT id as id, rfc, correo FROM act_c_clientes $whereClause LIMIT 1", "id");

    if(empty($_valcliente)) {
        // Construir consulta UPDATE dinámica
        $updates = [];
        $updates[] = "alias = '$alias'";
        $updates[] = "razon_social = '$razon_social'";
        
        // Campos opcionales (solo si se proporcionan)
        if(!empty($rfc)) {
            $updates[] = "rfc = '$rfc'";
        } else {
            $updates[] = "rfc = NULL";
        }
        
        if(!empty($domicilio)) {
            $updates[] = "domicilio = '$domicilio'";
        } else {
            $updates[] = "domicilio = NULL";
        }
        
        if(!empty($contacto)) {
            $updates[] = "contacto = '$contacto'";
        } else {
            $updates[] = "contacto = NULL";
        }
        
        if(!empty($correo)) {
            $updates[] = "correo = '$correo'";
        } else {
            $updates[] = "correo = NULL";
        }
        
        if(!empty($telefono)) {
            $updates[] = "telefono = '$telefono'";
        } else {
            $updates[] = "telefono = NULL";
        }

        // Ejecutar actualización
        $update = "UPDATE act_c_clientes SET " . implode(", ", $updates) . " WHERE id = '$id'";
        
        if($db->query($update)) {
            $arr = array('codigo' => 1, 'alerta' => 'Cliente actualizado correctamente');
        } else {
            throw new Exception("Error al actualizar cliente: " . $db->error);
        }
    } else {
        // Verificar qué campo está duplicado
        $alerta = "";
        if(!empty($rfc) && isset($_valcliente[1]['rfc']) && strtolower($rfc) == strtolower($_valcliente[1]['rfc'])) {
            $alerta = "<b>Error!</b> Ya existe otro cliente con el RFC: $rfc";
        }
        if(!empty($correo) && isset($_valcliente[1]['correo']) && strtolower($correo) == strtolower($_valcliente[1]['correo'])) {
            $alerta .= $alerta ? "<br>" : "";
            $alerta .= "<b>Error!</b> Ya existe otro cliente con el correo: $correo";
        }
        
        $arr = array('codigo' => 0, 'alerta' => $alerta ?: 'Error de duplicación no especificado');
    }
} catch(Exception $e) {
    $arr = array('codigo' => 0, 'alerta' => $e->getMessage());
}

echo json_encode($arr);
?>