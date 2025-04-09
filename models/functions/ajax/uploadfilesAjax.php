<?php
// Configuración básica
$modo = $db->real_escape_string($_POST['modo']);
$id = $db->real_escape_string($_POST['id']);

$dir = '';

switch ($modo) {
    case 'actividades_adjuntos':
        $dir = "views/images/attachments/".substr($id,0,2)."/".$id."/";
    break;
        
    default:
    $response = [
        'status' => 'error',
        'message' => 'No se han subido archivos modo no valido ['.$modo.']',
        'files' => []
    ];
        echo json_encode($response);
        return;
    
}

$uploadDir = $dir;

$allowedTypes = ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
$maxSize = 2 * 1024 * 1024; // 2MB

// Respuesta inicial
$response = [
    'status' => 'error',
    'message' => 'No se han subido archivos',
    'files' => []
];

// Verificar si hay archivos subidos
if (!empty($_FILES['archivos'])) {
    $files = $_FILES['archivos'];
    $successCount = 0;
    
    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Procesar cada archivo
    for ($i = 0; $i < count($files['name']); $i++) {
        $fileName = $files['name'][$i];
        $fileTmp = $files['tmp_name'][$i];
        $fileSize = $files['size'][$i];
        $fileError = $files['error'][$i];
        
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validaciones
        if ($fileError !== UPLOAD_ERR_OK) {
            $response['files'][$fileName] = 'Error al subir el archivo';
            continue;
        }
        
        if (!in_array($fileExt, $allowedTypes)) {
            $response['files'][$fileName] = 'Tipo de archivo no permitido';
            continue;
        }
        
        if ($fileSize > $maxSize) {
            $response['files'][$fileName] = 'El archivo excede el tamaño máximo';
            continue;
        }
        
        // Generar nombre único
        $newFileName = uniqid() . '.' . $fileExt;
        
        switch ($modo) {
            case 'actividades_adjuntos':
                $newFileName = $id.uniqid().'.'.$fileExt;              
            break;
                
            default:
            $response = [
                'status' => 'error',
                'message' => 'No se han subido archivos modo no Valido ['.$modo.']',
                'files' => []
            ];
                echo json_encode($response);
                return;
            
        }

        $destination = $uploadDir . $newFileName;


        // Mover archivo
        if (move_uploaded_file($fileTmp, $destination)) {
            $response['files'][$fileName] = 'Subido correctamente como ' . $newFileName;
            $successCount++;

            $q ='';
            switch ($modo) {
                case 'actividades_adjuntos':
                    $q = "INSERT INTO act_r_adjuntos (folio_act, id_u_registra, fh_registra, dir) VALUES 
                                                    ('$id', '".USER_ID."', now(), '$newFileName');";
                break;
                    
                default:
                $response = [
                    'status' => 'error',
                    'message' => 'No se han subido archivos modo no Valido ['.$modo.']',
                    'files' => []
                ];
                    echo json_encode($response);
                    return;
                
            }

            if($q!=''){
                $db->query($q);                
                if ($db->error) {
                    try {
                        throw new Exception("MySQL error $db->error <br> Query:<br> " , $db->errno);
                    } catch(Exception $e) {
                        $resultado .= "Error no. ".$e-> getCode(). "-" .$e->getMessage() . "<br>";
                        $resultado .= nl2br($e->getTraceAsString());
        
                        $response = [
                            'status' => 'error',
                            'message' => 'No se han Registrado archivo en folio.'.$resultado,
                            'files' => []
                        ];
                            echo json_encode($response);
                            return;
                    }
                }
            }

        } else {
            $response['files'][$fileName] = 'Error al guardar el archivo';
        }
    }
    
    if ($successCount > 0) {
        $response['status'] = 'success';
        $response['message'] = $successCount . ' archivo(s) subido(s) correctamente';
    } else {
        $response['message'] = 'Ningún archivo se pudo subir';
    }
}

// Devolver respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);
?>