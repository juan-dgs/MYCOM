<?php
  $db = new Conexion();

  $user = $db->real_escape_string($_POST['user']);
  $pass = Encrypt($_POST['pass']);//function Encrypt encripta la contraseña
  $session = $db->real_escape_string($_POST['session']);

  $sql = $db->query("SELECT id FROM users WHERE usuario='$user' AND clave='$pass' LIMIT 1;");

  if($db->rows($sql) > 0) {
    if($session) { //revisa si la variable de sesion es verdadera.
      ini_set('session.cookie_lifetime', time() + (60*60)); // recordar la session 60*60 = una hora
    } 

    $res = $db->recorrer($sql);

    $_SESSION['user_id'] = $res[0];

    $code = "1";
    $alert = 'x';
  }else{
    $code = "E0001";//ERROR DE LOGEO
    $alert = '<b>ERROR!!</b> Los Datos Introducidos no son validos.';
  }

  $db->liberar($sql);
  $db->close();

  $arr = array('CODE' => $code, 'ERROR' => $alert);

  echo json_encode($arr);
?>
