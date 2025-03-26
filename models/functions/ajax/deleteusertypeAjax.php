
<?php
$db = new Conexion();

$arr = array('codigo' => '', 'alerta' => '');

$id = $db->real_escape_string($_POST['id']);

$dt_valtype=findtablaq("SELECT 1 as id,codigo FROM users_types WHERE id=$id and activo =1 LIMIT 1;","id");

  if(($dt_valtype!=false)) {

    $dt_valusuarios=findtablaq("SELECT id FROM users WHERE c_tipo_usuario = '".$dt_valtype[1]['codigo']."' and activo = 1;","id");
if(($dt_valusuarios==false)) {
    $q = "UPDATE users_types SET activo = '0' WHERE users_types.id =$id;";

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
      $arr = array('codigo' => 1, 'alerta' => 'Se desactivo tipo De usuario Correctamente.');
    }
}else {
    $dato = '';

    $code = 0;
    $alerta = "";

        $alerta= "<b>Error!</b>Existen usuarios con el tipo de usuario que desea eliminar ";



    $arr = array('codigo' => $code, 'alerta' => $alerta);

}
  }else {
    $dato = '';

    $code = 0;
    $alerta = "";

        $alerta= "<b>Error!</b>El tipo de usuario que desea eliminar no existe ". $id .".";



    $arr = array('codigo' => $code, 'alerta' => $alerta);
  }

  echo json_encode($arr);
 ?>
