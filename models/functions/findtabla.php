<?php
function findtabla($tb,$id){
    $db = new Conexion ();
    $sql = $db ->query("SELECT * FROM $tb ; ");

    if ($db->rows($sql)>0){
      while ($data=$db->recorrer($sql)) {
        $datos[$data[$id]]=$data;
      }
    } else {
      $datos=false;
    }

    $db->liberar($sql);
    $db->close();

    return $datos;

}

 ?>
