<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

// Sanitizar el ID recibido
$id = $db->real_escape_string($_POST['id']);
$ruta_imagen = $db->real_escape_string($_POST['url']);

$respuesta='';
$codigo = 1;
// Verifica:
// 1. Que el archivo exista
// 2. Que sea un archivo (no directorio)
// 3. Que tenga extensión permitida (ej: .jpg, .png)
if (file_exists($ruta_imagen) && is_file($ruta_imagen)) {
    $extensiones_permitidas = ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    $extension = strtolower(pathinfo($ruta_imagen, PATHINFO_EXTENSION));
    
    if (in_array($extension, $extensiones_permitidas)) {
        if (unlink($ruta_imagen)) {
            $respuesta= "Adjunto eliminada con éxito.";
        } else {
             $respuesta= "Error en el borrado (permisos).";
             $codigo = 0;
        }
    } else {
         $respuesta= "Extensión no permitida.";
         $codigo = 0;
    }
} else {
     $respuesta= "Archivo no encontrado o inválido.[".$ruta_imagen."]";
     $codigo = 0;
}

if ($codigo ==1){
    $q = "DELETE FROM act_r_adjuntos WHERE id = $id";
    $db->query($q);
    if ($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> ", $db->errno);
        } catch(Exception $e) {
            $resultado = "Error no. ".$e->getCode()."-".$e->getMessage()."<br>";
            $resultado .= nl2br($e->getTraceAsString());

            $alerta = '<b>Error!</b> '.$resultado;
            $arr = array('codigo' => 0, 'alerta' => $alerta);
        }
    }
}

$arr = array('codigo' => $codigo, 'alerta' => $respuesta);
echo json_encode($arr);
?>