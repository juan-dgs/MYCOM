<?php
function findtablaq($q, $id) {
  $db = new Conexion();
  $sql = $db->query($q);
  $datos = false;

  if ($db->rows($sql) > 0) {
      while ($data = $db->recorrer($sql)) {
          // Eliminar índices numéricos y conservar solo los asociativos
          /*$datos_filtrados = array_filter($data, function($key) {
              return !is_numeric($key); // Conserva solo claves no numéricas
          }, ARRAY_FILTER_USE_KEY);*/
          
          $datos[$data[$id]] = $data;
      }
  }else{
    $datos=false;
  }

  $db->liberar($sql);
  $db->close();

  return $datos;
}

/*
function findtablaq($q,$id){
    $db = new Conexion ();
    $sql = $db ->query($q);

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

}*/

 ?>
