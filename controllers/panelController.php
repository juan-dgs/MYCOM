<?php

  if(isset($_SESSION['user_id'])) {
    if(isset($_GET['view'])) {

      $qPermiso = "SELECT 1 as id,m.dir , if(m.tipo='CAT',concat('Catalogo ',m.titulo), m.titulo) as titulo,
                          IF(m.c_modulo in (SELECT p.c_modulo FROM menu_permission as p WHERE p.c_tipo_usuario='".USER_TYPE."') or m.nivel1=0, 1,0) AS permiso
                      FROM menu as m
                      WHERE m.activo =1 and m.vinculo ='".$_GET['view']."' LIMIT 1;";



     $_programadirectorio=findtablaq($qPermiso,"id");


      $dir = PANEL.$_programadirectorio[1]['dir'];

      if(file_exists($dir) && $_programadirectorio[1]['dir'] != '' && $_programadirectorio[1]['permiso'] == 1){

        define('TITULO_MODULO', $_programadirectorio[1]['titulo']);

         include($dir);
         //echo $dir.$qPermiso;
       }else{
         header('location: panel');
       }


   }else{
     header('location: home');
   }
  }else {
     header('location: home');
  }
  //echo $_usuarios[$_SESSION['app_id']]["Nombre"];
?>
