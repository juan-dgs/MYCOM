<?php
  // clase realiza la conexion y las funciones con la conexion con la base de datos
  class Conexion extends mysqli {

    public function __construct() {
      parent::__construct(DB_HOST,DB_USER,DB_PASS,DB_NAME);
      $this->connect_errno ? die('Error en la conexión a la base de datos') : $x = 'Conectado'  ;
      //echo $x;
      unset($x);
      $this->set_charset("utf8");
    }

    public function rows($query) {
      return mysqli_num_rows($query);
    }

    public function liberar($query) {
      return mysqli_free_result($query);
    }

  //Metodo para debolber en forma de array los datos en la bd
    public function recorrer($query) {
      return mysqli_fetch_array($query);
    }

  }
  $db = new Conexion;

?>
