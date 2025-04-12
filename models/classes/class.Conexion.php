<?php
  
  
  /*// clase realiza la conexion y las funciones con la conexion con la base de datos
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
  $db = new Conexion;*/

  class Conexion extends mysqli {
    public function __construct() {
        parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connect_errno) {
            die('Error en la conexión a la base de datos');
        }
        $this->set_charset("utf8");
    }

    public function rows($query) {
        return $query instanceof mysqli_result ? mysqli_num_rows($query) : 0;
    }

    public function liberar($query) {
        if ($query instanceof mysqli_result) {
            mysqli_free_result($query);
            return true;
        }
        return false;
    }

    public function recorrer($query) {
        return $query instanceof mysqli_result ? mysqli_fetch_array($query) : false;
    }

    // Nuevo método específico para procedimientos almacenados
    public function callProcedure($procedureName, $params = []) {
        $placeholders = str_repeat('?,', count($params));
        $placeholders = rtrim($placeholders, ',');
        
        $sql = "CALL $procedureName($placeholders)";
        $stmt = $this->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error al preparar el procedimiento: " . $this->error);
        }
        
        // Bind parameters dinámicamente
        if (!empty($params)) {
            $types = '';
            $bindParams = [&$types];
            
            foreach ($params as &$param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $bindParams[] = &$param;
            }
            
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $results = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            $this->liberar($result);
        }
        
        // Manejar múltiples resultados
        while ($stmt->more_results()) {
            $stmt->next_result();
            if ($additionalResult = $stmt->get_result()) {
                $this->liberar($additionalResult);
            }
        }
        
        $stmt->close();
        return $results;
    }
}

$db = new Conexion();

?>
