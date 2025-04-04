<?php
$db = new Conexion();

// Verificar si se recibió el ID
if(!isset($_POST['id']) || empty(trim($_POST['id']))) {
    die(json_encode(['error' => 'No se recibió un ID válido']));
}

$id = $db->real_escape_string($_POST['id']);

try {
    // Consulta para obtener los datos de la prioridad
    $query = "SELECT id, codigo, descripcion, color_hex, hr_min, hr_max, icono 
              FROM act_c_prioridades 
              WHERE id = '$id' AND activo = 1 
              LIMIT 1";
    
    $result = $db->query($query);
    
    if(!$result) {
        throw new Exception("Error en la consulta: " . $db->error);
    }
    
    if($result->num_rows === 0) {
        die(json_encode(['error' => 'La prioridad no existe o está inactiva']));
    }
    
    $data = $result->fetch_assoc();
    
    // Construir el formulario de edición
    $formHTML = '<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Código:</label>
                <input type="text" class="form-control" value="'.htmlspecialchars($data['codigo'] ?? '').'" readonly>
            </div>
            
            <div class="form-group">
                <label for="edit_descripcion">Descripción:</label>
                <input type="text" class="form-control" id="edit_descripcion" 
                       value="'.htmlspecialchars($data['descripcion'] ?? '').'" required>
            </div>
            
            <div class="form-group">
                <label for="edit_color_hex">Color:</label>
                <input type="color" class="form-control" id="edit_color_hex" 
                       value="'.htmlspecialchars($data['color_hex'] ?? '#FF0000').'">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="edit_hr_min">Horas Mínimas:</label>
                <input type="number" class="form-control" id="edit_hr_min" 
                       value="'.htmlspecialchars($data['hr_min'] ?? '0').'" 
                       step="0.5" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="edit_hr_max">Horas Máximas:</label>
                <input type="number" class="form-control" id="edit_hr_max" 
                       value="'.htmlspecialchars($data['hr_max'] ?? '0').'" 
                       step="0.5" min="0" required>
            </div>
            
            <div class="form-group">
                <label>Ícono:</label>
                <div class="input-group">
                    <input type="hidden" id="edit_icono" value="'.htmlspecialchars($data['icono'] ?? '').'">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#iconModal">
                        <i class="fas fa-icons"></i> Seleccionar
                    </button>
                </div>
                <div id="editIconPreview" class="mt-2" style="font-size: 24px;">
                    '.(!empty($data['icono']) 
                        ? '<i class="'.htmlspecialchars($data['icono']).'"></i>'
                        : '<i class="fas fa-question-circle text-muted"></i>').'
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="edit_id" value="'.htmlspecialchars($data['id']).'">';
    
    echo json_encode(['success' => true, 'html' => $formHTML]);
    
} catch(Exception $e) {
    error_log("Error en getpriorityAjax.php: " . $e->getMessage());
    die(json_encode(['error' => 'Error al cargar los datos']));
}
?>