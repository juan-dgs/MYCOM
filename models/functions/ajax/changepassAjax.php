<?php
  $db = new Conexion();

  $id = $db->real_escape_string($_POST['id']);
  $clave = Encrypt($db->real_escape_string($_POST['clave']));

  $update = "UPDATE users SET clave = '$clave' WHERE id = '$id';";

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
    $arr = array('codigo' => $code, 'alerta' => '<b>Completado!</b> Se edito correctamente la contraseña.');
  }

  echo json_encode($arr);


 ?>