<?php
   //if($_POST) {
     require ('models/core/core.php');
     if (isset($_GET['mode'])) {
       // Nuevos casos específicos para prioridades
       switch(strtolower($_GET['mode'])) {
         case 'getpriority':
           require('models/functions/ajax/getpriorityAjax.php');
           break;
         case 'newpriority':
           require('models/functions/ajax/newpriorityAjax.php');
           break;
         case 'savepriority':
           require('models/functions/ajax/savepriorityAjax.php');
           break;
         case 'deletepriority':
           require('models/functions/ajax/deletepriorityAjax.php');
           break;
         default:
           // Comportamiento original para otros casos
           if(file_exists('models/functions/ajax/'. strtolower($_GET['mode']) . 'Ajax.php')){
               require ('models/functions/ajax/' . strtolower($_GET['mode']) . 'Ajax.php');
           }else{
               echo('{"ERROR":"Ajax Error3 - NO EXISTE FUNCION AJAX ('. $_GET['mode'] .')"}');
           }
           break;
       }
     }else{
       echo('{"ERROR":"Ajax Error2 - FALTA DE PARAMETRO MODE"}');
     }
   /*}else {
     //header('location: index.php');
     echo("<script>console.log('Ajax Error1');</script>");
   }*/
?>