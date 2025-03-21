
<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$nombres = $db->real_escape_string($_POST['nombres']);
$apellido_p = $db->real_escape_string($_POST['apellido_p']);
$apellido_m = $db->real_escape_string($_POST['apellido_m']);
$usuario = $db->real_escape_string($_POST['usuario']);
$clave = Encrypt($db->real_escape_string($_POST['clave']));
$correo = $db->real_escape_string($_POST['correo']);
$c_tipo_usuario = $db->real_escape_string($_POST['c_tipo_usuario']);



$_valusuario=findtablaq("SELECT 1 as id,usuario,correo FROM users WHERE usuario='$usuario' OR correo='$correo' LIMIT 1;","id");


  if(empty($_valusuario)) {
    $q = "INSERT INTO users ( nombre, apellido_p, apellido_m, dir_foto, c_tipo_usuario, f_registro, usuario, clave, correo)
            VALUES ( '$nombres', '$apellido_p', '$apellido_m', '', '$c_tipo_usuario', NOW(), '$usuario', '$clave', '$correo');";

    $db->query($q);

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
      $arr = array('codigo' => 1, 'alerta' => 'Se Genero Usuario Correctamente.');
    }
  }else {
    $dato = '';

    $code = 0;
    $alerta = "";

    if(strtolower($usuario)==strtolower($_valusuario[1]["usuario"])){
        $alerta= "<b>Error!</b>Hay un usuario que coincide con ". $usuario .". ";
    }else if(strtolower($correo)==strtolower($_valusuario[1]["correo"])){
        $alerta= "<b>Error!</b>Hay un correo que coincide con ". $correo .". ";
    }


    $arr = array('codigo' => $code, 'alerta' => $alerta);
  }

  echo json_encode($arr);

?>

