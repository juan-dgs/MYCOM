<?php
$tabla = $db->real_escape_string($_POST['tabla']);
$campoId =$db->real_escape_string($_POST['campoId']);
$datoId = $db->real_escape_string($_POST['datoId']);


$dt_register=findtablaq("SELECT *
                          FROM $tabla
                          WHERE $campoId= '$datoId' LIMIT 1;","id");

if ($dt_register!=false){
  echo json_encode($dt_register);
}else{
  $arr = array('CODE' => 0, 'ERROR' => 'ERROR:NO HAY REGISTROS.'. "SELECT *
                          FROM $tabla
                          WHERE $campoId= '$datoId' LIMIT 1;");
  echo json_encode($arr);
}
 ?>
