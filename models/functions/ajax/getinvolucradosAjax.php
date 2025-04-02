<?php

$folio = $db->real_escape_string($_POST['folio']);

$dt_involucrados=findtablaq("SELECT 1 as id, GROUP_CONCAT(DISTINCT id_usuario) as ids_usuarios
                        FROM act_r_involucrados
                        WHERE folio='$folio';","id");
$arr;
if ($dt_involucrados!=false){
    $arr = array('CODE' => 1, 'INVOLUCRADDOS' => explode(",", $dt_involucrados[1]['ids_usuarios']));
}else{
  $arr = array('CODE' => 0, 'ERROR' => 'ERROR:NO HAY REGISTROS.');
}

echo json_encode($arr);

 ?>
