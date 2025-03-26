
<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');
$c_tipo_usuario = $db->real_escape_string($_POST['c_tipo_usuario']);
$descripcion = $db->real_escape_string($_POST['descripcion']);



$_valtipo=findtablaq("SELECT 1 as id,codigo,descripcion FROM users_types WHERE codigo='$c_tipo_usuario' OR descripcion='$descripcion' LIMIT 1;","id");


  if(empty($_valtipo)) {
    $q = "INSERT INTO users_types (codigo, descripcion)
            VALUES ('$c_tipo_usuario', '$descripcion');";

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
      $arr = array('codigo' => 1, 'alerta' => 'Se Genero Tipo De Usuario Correctamente.');
    }
  }else {
    $dato = '';

    $code = 0;
    $alerta = "";

    if(strtolower($c_tipo_usuario)==strtolower($_valtipo[1]["codigo"])){
        $alerta= "<b>Error!</b>Hay un codigo que coincide con ". $c_tipo_usuario .". ";
    }else if(strtolower($descripcion)==strtolower($_valtipo[1]["descripcion"])){
        $alerta= "<b>Error!</b>Hay una descripcion que coincide con ". $descripcion .". ";
    }


    $arr = array('codigo' => $code, 'alerta' => $alerta);
  }

  echo json_encode($arr);

?>

