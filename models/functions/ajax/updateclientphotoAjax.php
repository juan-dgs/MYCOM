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
            $nombreFoto = "client_".$id."_".time().".".$ext;
            $ruta = "views/images/clients/".$nombreFoto;
            
            // Crear directorio si no existe
            if (!file_exists("views/images/clients/")) {
                mkdir("views/images/clients/", 0755, true);
            }
            
            // Mover el archivo subido al directorio destino
            if(move_uploaded_file($foto['tmp_name'], $ruta)) {
                // Primero obtener la foto anterior para eliminarla
                $queryFotoAnterior = "SELECT dir_logo FROM act_c_clientes WHERE id = '$id'";
                $result = $db->query($queryFotoAnterior);
                $fotoAnterior = $result->fetch_assoc()['dir_logo'];
                
                // Eliminar la foto anterior si existe y no es la default
                if($fotoAnterior && $fotoAnterior != 'clientDefault.png') {
                    $rutaAnterior = "views/images/clients/".$fotoAnterior;
                    if(file_exists($rutaAnterior) && !is_dir($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                }
                
                // Actualizar en la base de datos
                $update = "UPDATE act_c_clientes SET dir_logo = '$nombreFoto' WHERE id = '$id'";
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
            } else {
                $arr = array(
                    'codigo' => 0, 
                    'alerta' => '<b>Error!</b> No se pudo mover el archivo al directorio destino.'
                );
            }
        } else {
            $arr = array(
                'codigo' => 0, 
                'alerta' => '<b>Error!</b> El tamaño de la imagen excede el límite permitido (2MB).'
            );
        }
    } else {
        $arr = array(
            'codigo' => 0, 
            'alerta' => '<b>Error!</b> Formato de imagen no permitido. Solo se aceptan JPG, PNG o GIF.'
        );
    }
} else {
    $arr = array(
        'codigo' => 0, 
        'alerta' => '<b>Error!</b> Datos incompletos. No se recibió la imagen o el ID de cliente.'
    );
}
echo json_encode($arr);
?>