<?php
class Database {
    private $host = 'localhost';
    private $port = '5432';
    private $dbname = 'lmserk';
    private $user = 'postgres';
    private $password   = '@2512ErkMco01';
   // private $password = 'root';//'@2512ErkMco01';
    private $conn;

    // Conectar a la base de datos
    public function connect() {
        try {
            $this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
            // Establecer el modo de error en excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
           // echo 'Error de conexión: ' . $e->getMessage();
            return null;
        }
    }

    // Insertar un registro en la tabla
    public function insert($tabla, $datos, $retornarId = false) {
        $columnas = implode(", ", array_keys($datos));
        $valores = ":" . implode(", :", array_keys($datos));
        $sql = "INSERT INTO $tabla ($columnas) VALUES ($valores)";
    
        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($datos as $columna => $valor) {
                if ($columna === 'fotografia' || $columna === 'logo' || $columna === "logo_thumb") {
                    // Asegúrate de que el valor se trate como datos binarios
                    $stmt->bindValue(":$columna", $valor, PDO::PARAM_LOB);
                } else {
                    $stmt->bindValue(":$columna", $valor);
                }
            }
            $stmt->execute();
            
            // Retornar el ID del último registro insertado si se solicita
            if ($retornarId) {
                return $this->conn->lastInsertId();
            }
            return true;
        } catch (PDOException $e) {
            //echo 'Error al insertar: ' . $e->getMessage();
            return false;
        }
    }
    

   // Actualizar un registro en la tabla
public function update($tabla, $datos, $condicion) {
    $campos = [];
    foreach ($datos as $columna => $valor) {
        $campos[] = "$columna = :$columna";
    }
    $campos_sql = implode(", ", $campos);
    $sql = "UPDATE $tabla SET $campos_sql WHERE $condicion";

    try {
        $stmt = $this->conn->prepare($sql);
        foreach ($datos as $columna => $valor) {
            $stmt->bindValue(":$columna", $valor);
        }
        $stmt->execute();
        
        // Retornar el número de filas afectadas
        return $stmt->rowCount();
    } catch (PDOException $e) {
        // echo 'Error al actualizar: ' . $e->getMessage();
        return -1;
    }
}


    // Eliminar un registro de la tabla
    public function del($tabla, $condicion) {
        $sql = "DELETE FROM $tabla WHERE $condicion";

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute();
        } catch (PDOException $e) {
           // echo 'Error al eliminar: ' . $e->getMessage();
            return array("error"=>true,"msg"=> $e->getMessage());
        }
    }

    // Seleccionar registros de la tabla
    public function getAll($tabla, $columnas = '*', $condicion = '1=1') {
        $sql = "SELECT $columnas FROM $tabla WHERE $condicion";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
           // echo 'Error al seleccionar: ' . $e->getMessage();
            return [];
        }
    }

    // Seleccionar registros de la tabla
    public function exec($sql) {

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
           // echo 'Error al seleccionar: ' . $e->getMessage();
            return [];
        }
    }
    // Seleccionar registros de la tabla
    public function get($tabla, $columnas = '*', $condicion = '1=1') {
        $sql = "SELECT $columnas FROM $tabla WHERE $condicion";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
           // echo 'Error al seleccionar: ' . $e->getMessage();
            return null;
        }
    }
    public function getCol($tabla, $columnas = '*', $condicion = '1=1') {
        $sql = "SELECT $columnas FROM $tabla WHERE $condicion";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn(); 
        } catch (PDOException $e) {
           // echo 'Error al seleccionar: ' . $e->getMessage();
            return 0;
        }
    }
    function imgFormato($hex) {
        if (strpos($hex, 'ffd8') === 0) {
            return 'jpeg';
        } elseif (strpos($hex, '89504e47') === 0) {
            return 'png';
        } elseif (strpos($hex, '47494638') === 0) {
            return 'gif';
        } elseif (strpos($hex, '49492a00') === 0 || strpos($hex, '4d4d002a') === 0) {
            return 'tiff';
        } elseif (strpos($hex, '424d') === 0) {
            return 'bmp';
        } else {
            return 'unknown';
        }
    }
}

$db = new Database();
$conn = $db->connect();


?>
