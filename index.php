<?php
//INICIO DEL SITIO - CORE - PROCESADOR DE LOS CONTROLADORES
 require ('models/core/core.php');
  $dir = HTML;
  $_core=findtablaq('SELECT id,bloqueo FROM core','id');

  if ($_core!=false){
    if($_core[1]['bloqueo']==0){
      if(isset($_SESSION['user_id'])) {
        if (isset($_GET['view'])){
          if($_GET['view'] != 'logout'){
            include('controllers/panelController.php');
          }else{
            include('controllers/logoutController.php');
          }
        }else {
          header('location: panel');
        }
      }else {
        if (isset($_GET['view'])) {
          if(file_exists(HTML. strtolower($_GET['view']).'.php')){
              include(HTML.strtolower($_GET['view']).".php");
          }else{
            header('location: home');
          }
        }else{
          header('location: home');
        }
      }
    }else {
      if($_GET['view']=='securepay'){
        include ('controllers/' . strtolower ($_GET['view']) . 'Controller.php');
      }else{
         include(HTML."/master/error404.php");
      }
    }
  }else {
    include(HTML."/master/error404.php");
  }


 ?>
