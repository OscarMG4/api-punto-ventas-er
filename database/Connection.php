<?php

class Connection {
    private static $instance;
    private $conn;
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $password;

    private function __construct() {
        $this->host = 'localhost';
        $this->port = '5432';
        $this->dbname = 'bd_punto_ventas';
        $this->user = 'postgres';
        $this->password = 'root';

        try {
            $this->conn = new PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->conn;
    }
}

// Crear una instancia de la base de datos
//$database = Connection::getInstance();

// Obtener la conexión y verificar
//$conn = $database->getConnection();

//if ($conn) {
    //echo "Conexión establecida";
//} else {
    //echo "Error en la conexión";
//}
