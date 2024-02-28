<?php

require_once(__DIR__ . '/../database/Connection.php');
header('Access-Control-Allow-Origin: *');

class UsuarioModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getAllUsuarios()
    {
        try {
            $query = $this->conn->query('SELECT * FROM tb_usuarios');
            return $query->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener todos los usuarios: " . $e->getMessage());
        }
    }

    public function getUsuarioById($id)
    {
        try {
            $query = $this->conn->prepare('SELECT * FROM tb_usuarios WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el usuario por ID: " . $e->getMessage());
        }
    }

    public function createUsuario($usuario, $contrasenia, $activo): bool
    {
        try {
            // Encriptar la contraseña usando password_hash
            $hashedPassword = password_hash($contrasenia, PASSWORD_DEFAULT);

            $query = $this->conn->prepare('INSERT INTO tb_usuarios (usuario, contrasenia, activo) VALUES (:usuario, :contrasenia, :activo)');
            $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $query->bindParam(':contrasenia', $hashedPassword, PDO::PARAM_STR);
            $query->bindParam(':activo', $activo, PDO::PARAM_BOOL);

            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al crear el usuario: " . $e->getMessage());
        }
    }


    public function updateUsuario($id, $usuario, $contrasenia, $activo): bool
    {
        try {
            $query = $this->conn->prepare('UPDATE tb_usuarios SET usuario = :usuario, contrasenia = :contrasenia, activo = :activo WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $query->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
            $query->bindParam(':activo', $activo, PDO::PARAM_BOOL);
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }


    public function deleteUsuario($id): bool
    {
        try {
            $query = $this->conn->prepare('DELETE FROM tb_usuarios WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el usuario: " . $e->getMessage());
        }
    }

    public function getUsuarioByCredentials($usuario, $contrasenia)
    {
        try {
            $query = $this->conn->prepare('SELECT * FROM tb_usuarios WHERE usuario = :usuario');
            $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch();

            if ($user && password_verify($contrasenia, $user['contrasenia'])) {
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el usuario por credenciales: " . $e->getMessage());
        }
    }
}
