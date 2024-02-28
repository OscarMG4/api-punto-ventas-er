<?php
require_once(__DIR__ . '/../database/Connection.php');

class LoginModel {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function validateLogin($usuario, $contrasenia): bool {
        try {
            // Consultar la base de datos para verificar las credenciales y el estado del usuario
            $query = $this->conn->prepare('SELECT * FROM tb_usuarios WHERE usuario = :usuario');
            $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch();

            if ($user) {
                if ($user['activo']) {
                    if (password_verify($contrasenia, $user['contrasenia'])) {
                        return true; 
                    } else {
                        return false; 
                    }
                } else {
                    return false; 
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al validar el inicio de sesión: " . $e->getMessage());
        }
    }
}