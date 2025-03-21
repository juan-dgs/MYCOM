
<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$id = $db->real_escape_string($_POST['id']);

$dt_valusuario=findtablaq("SELECT id FROM users WHERE id=$id and activo =1 LIMIT 1;","id");

  if(($dt_valusuario!=false)) {
    $q = "UPDATE users SET activo = '0',f_inactivo= now() WHERE users.id =$id;";

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
      $arr = array('codigo' => 1, 'alerta' => 'Se desactivo Usuario Correctamente.');
    }
  }else {
    $dato = '';

    $code = 0;
    $alerta = "";

        $alerta= "<b>Error!</b>El usuario que desea eliminar no existe ". $id .". "."SELECT id FROM users WHERE id='$id' and activo =1 LIMIT 1;";



    $arr = array('codigo' => $code, 'alerta' => $alerta);
  }

  echo json_encode($arr);
 ?>
