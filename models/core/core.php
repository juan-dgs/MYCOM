<?php
/*NUCLEO DE LA APLICACION*/
  session_start();
  date_default_timezone_set('America/Mexico_City'); // Cambia por tu zona
  setlocale(LC_TIME, 'es_VE.UTF-8','esp');


  define('TITULO','MYCOM');
  define('CYR', 'Copyright &copy; ' .date('Y',time()) );
  define('HTML', 'views/html/');
  define('PANEL', 'views/html/AdminPanel/');
  define('ADMINLTE', 'views/components/AdminLTE-3.2.0/');
  define('IMG','views/imagenes/');

  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'db_template');
  
  /*define('DB_HOST', 'localhost');
  define('DB_USER', 'contador_system');
  define('DB_PASS', '%_OPVA-UzWS&');
  define('DB_NAME', 'contador_mycom');*/

  /*
  define('CORREO','pruebas@antaudacity.com');
  define('PASS', '123456789');
  define('PORT', '465');
  define('HOST', 'localhost');
  */


  require('models/classes/class.Conexion.php');
  require('models/functions/findtabla.php');
  require('models/functions/findtablaquery.php');
  require('models/functions/Encrypt.php');
  require('models/functions/funcionesGeneral.php');

  $_MESES = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
  $_DIASSEM = ['','Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

  if(isset($_SESSION['user_id'])){
      $_usuario=findtablaq("SELECT u.id,u.nombre,u.apellido_p,u.dir_foto,u.c_tipo_usuario,ut.descripcion as DTIPOU,YEAR(u.f_registro) AS yreg
                          ,MONTH(u.f_registro) AS mreg FROM users as u , users_types as ut
                            WHERE ut.codigo = u.c_tipo_usuario and u.id = '".$_SESSION['user_id']."' LIMIT 1; ","id");



      define('MY_REGISTRO',$_MESES[$_usuario[$_SESSION['user_id']]['mreg']].' , '.$_usuario[$_SESSION['user_id']]['yreg']);
      define('USER_ID',$_SESSION['user_id']);
      define('USER_NAME',explode(" ",$_usuario[$_SESSION['user_id']]['nombre'])[0].' '.$_usuario[$_SESSION['user_id']]['apellido_p']);
      define('USER_TYPE',$_usuario[$_SESSION['user_id']]['c_tipo_usuario']);
      define('USER_TYPE_DESC',$_usuario[$_SESSION['user_id']]['DTIPOU']);

      if($_usuario[$_SESSION['user_id']]['dir_foto'] != ""){
        define('USER_PHOTO','views/images/profile/'.$_usuario[$_SESSION['user_id']]['dir_foto']);
      }else{
        define('USER_PHOTO','views/images/profile/userDefault.png');
      }
    }





   ?>
