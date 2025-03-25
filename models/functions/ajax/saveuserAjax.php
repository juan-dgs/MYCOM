<?php
$db = new Conexion();

$id = $db->real_escape_string($_POST['id']);

$nombres = $db->real_escape_string($_POST['nombres']);
$apellido_p = $db->real_escape_string($_POST['apellido_p']);
$apellido_m = $db->real_escape_string($_POST['apellido_m']);
$usuario = $db->real_escape_string($_POST['usuario']);
$correo = $db->real_escape_string($_POST['correo']);
$c_tipo_usuario = $db->real_escape_string($_POST['c_tipo_usuario']);


$_valusuario=findtablaq("SELECT 1 as id,usuario,correo 
                            FROM users 
                            WHERE (usuario='$usuario' 
                            OR correo='$correo') 
                            and id!= '$id' 
                            LIMIT 1;","id");


  if(empty($_valusuario)) {
    $update = "update users set nombre='$nombres', apellido_p='$apellido_p', apellido_m='$apellido_m', usuario='$usuario', correo='$correo', c_tipo_usuario='$c_tipo_usuario' 
            where id='$id';";

            $db->query($update);
            $resultado = "";
            if ($db->error) {
              try {
                throw new Exception("MySQL error $db->error <br> Query:<br> " , $db->errno);
              } catch(Exception $e) {
                $resultado .= "Error no. ".$e-> getCode(). "-" .$e->getMessage() . "<br>";
                $resultado .= nl2br($e->getTraceAsString());
          
                $alerta = '<b>Error!</b> '.$resultado;
                $arr = array('codigo' => 0, 'alerta' => $alerta);
              }
            }else{
              $code = 1;
              $arr = array('codigo' => $code, 'alerta' => '<b>Completado!</b> Se edito correctamente el usuario.');
            }
          
            echo json_encode($arr);          
       
  } else {
    $dato = '';

    $code = 0;
    $alerta = "";

    if(strtolower($usuario)==strtolower($_valusuario[1]["usuario"])){
        $alerta= "<b>Error!</b>Hay un usuario que coincide con ". $usuario .". ";
    }else if(strtolower($correo)==strtolower($_valusuario[1]["correo"])){
        $alerta= "<b>Error!</b>Hay un correo que coincide con ". $correo .". ";
    }


    $arr = array('codigo' => $code, 'alerta' => $alerta);
    echo json_encode($arr);          

  }

?>

