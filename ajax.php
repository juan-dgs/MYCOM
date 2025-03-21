 <?php
   //if($_POST) {
     require ('models/core/core.php');
     if (isset($_GET['mode'])) {
       if(file_exists('models/functions/ajax/'. strtolower ($_GET['mode']) . 'Ajax.php')){
           require ('models/functions/ajax/' . strtolower ($_GET['mode']) . 'Ajax.php');
       }else{
           echo('{"ERROR":"Ajax Error3 - NO EXISTE FUNCION AJAX ('. $_GET['mode'] .')"}');
       }
     }else{
       echo('{"ERROR":"Ajax Error2 - FALTA DE PARAMETRO MODE"}');
     }
   /*}else {
     //header('location: index.php');
     echo("<script>console.log('Ajax Error1');</script>");
   }*/
 ?>
