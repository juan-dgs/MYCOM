<?php
$db = new Conexion();

// Verificar si se recibió el archivo y el ID
if(isset($_FILES['foto']) && isset($_POST['id'])) {
    $id = $db->real_escape_string($_POST['id']);
    $foto = $_FILES['foto'];
    
    // Validar el tipo de archivo
    $permitidos = array("image/jpeg", "image/png", "image/gif");
    $limite_kb = 2048; // 2MB
    
    if(in_array($foto['type'], $permitidos)) {
        // Validar tamaño del archivo
        if($foto['size'] <= $limite_kb * 1024) {
            // Obtener la extensión del archivo
            $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
            
            // Generar nombre único para la imagen
            $nombreFoto = "user_".$id.uniqid().".".$ext;
            $ruta = "views/images/profile/".$nombreFoto;
            
            // Mover el archivo subido al directorio destino
            if(move_uploaded_file($foto['tmp_name'], $ruta)) {
                // Primero obtener la foto anterior para eliminarla
                $queryFotoAnterior = "SELECT dir_foto FROM users WHERE id = '$id'";
                $result = $db->query($queryFotoAnterior);
                $fotoAnterior = $result->fetch_assoc()['dir_foto'];
                
                // Eliminar la foto anterior si existe y no es la default
                if($fotoAnterior && $fotoAnterior != 'userDefault.png') {
                    $rutaAnterior = "views/images/profile/".$fotoAnterior;
                    if(file_exists($rutaAnterior)) {
                        borrarArchivoSeguro($rutaAnterior);
                    }
                }
                
                // Actualizar en la base de datos
                $update = "UPDATE users SET dir_foto = '$nombreFoto' WHERE id = '$id'";
                $db->query($update);
                
                $resultado = "";
                if ($db->error) {
                    try {
                        throw new Exception("MySQL error $db->error <br> Query:<br> " , $db->errno);
                    } catch(Exception $e) {
                        $resultado .= "Error no. ".$e->getCode(). "-" .$e->getMessage() . "<br>";
                        $resultado .= nl2br($e->getTraceAsString());
                  
                        $alerta = '<b>Error!</b> '.$resultado;
                        $arr = array('codigo' => 0, 'alerta' => $alerta);
                    }
                } else {
                    $code = 1;
                    $arr = array(
                        'codigo' => $code, 
                        'alerta' => '<b>Completado!</b> La foto se actualizó correctamente.',
                        'foto' => $nombreFoto
                    );
                }
                
                echo json_encode($arr);
            } else {
                $arr = array(
                    'codigo' => 0, 
                    'alerta' => '<b>Error!</b> No se pudo mover el archivo al directorio destino.'
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'codigo' => 0, 
                'alerta' => '<b>Error!</b> El tamaño de la imagen excede el límite permitido (2MB).'
            );
            echo json_encode($arr);
        }
    } else {
        $arr = array(
            'codigo' => 0, 
            'alerta' => '<b>Error!</b> Formato de imagen no permitido. Solo se aceptan JPG, PNG o GIF.'
        );
        echo json_encode($arr);
    }
} else {
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> Datos incompletos. No se recibió la imagen o el ID de usuario.'
    );
    echo json_encode($arr);
}
?>