<?php
  $db = new Conexion();

  $id = $db->real_escape_string($_POST['id']);
  $tabla = $db->real_escape_string($_POST['tabla']);
  $nameKey = $db->real_escape_string($_POST['nameKey']);
  $columna  = $db->real_escape_string($_POST['columna']);
  $txt  = $db->real_escape_string($_POST['txt']);

  $q = "UPDATE $tabla SET $columna = ".(strtolower($txt)=='now()'?$txt:"'".$txt."'")." WHERE $nameKey = '$id';";

  $db->query($q);
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
    $arr = array('codigo' => $code, 'alerta' => '<b>Completado!</b> Se edito correctamente un campo.');
  }

  echo json_encode($arr);


 ?>
