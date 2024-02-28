<?php

require_once(__DIR__ . '/../models/UsuarioModel.php');

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->handleGetRequest();
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            case 'PUT':
                $this->handlePutRequest();
                break;
            case 'DELETE':
                $this->handleDeleteRequest();
                break;
            default:
                http_response_code(405); // Método no permitido
                echo json_encode(['error' => 'Método no permitido']);
        }
    }

    private function handleGetRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/'; // Obtener la ruta 
        
        if (preg_match('/^\/usuarios\/(\d+)$/', $path, $matches)) {
            $this->getUsuarioById($matches[1]);
        } elseif ($path === '/usuarios') {
            $this->getAllUsuarios();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function handlePostRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if ($path === '/usuarios') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data) {
                $this->createUsuario($data);
            } else {
                http_response_code(400); // Solicitud incorrecta
                echo json_encode(['error' => 'Datos de solicitud no válidos']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function handlePutRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if (preg_match('/^\/usuarios\/(\d+)$/', $path, $matches)) {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data) {
                $this->updateUsuario($matches[1], $data);
            } else {
                http_response_code(400); // Solicitud incorrecta
                echo json_encode(['error' => 'Datos de solicitud no válidos']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function handleDeleteRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if (preg_match('/^\/usuarios\/(\d+)$/', $path, $matches)) {
            $this->deleteUsuario($matches[1]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }
    
    private function getAllUsuarios() {
        try {
            $usuarios = $this->usuarioModel->getAllUsuarios();
            header('Content-Type: application/json');
            echo json_encode($usuarios);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function getUsuarioById($id) {
        try {
            $usuario = $this->usuarioModel->getUsuarioById($id);
            header('Content-Type: application/json');

            if ($usuario) {
                echo json_encode($usuario);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario no encontrado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function createUsuario($data) {
        try {
            $usuario = $data['usuario'];
            $contrasenia = $data['contrasenia'];
            $activo = $data['activo'];

            $result = $this->usuarioModel->createUsuario($usuario, $contrasenia, $activo);

            if ($result) {
                echo json_encode(['message' => 'Usuario creado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear usuario']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function updateUsuario($id, $data) {
        try {
            $usuario = $data['usuario'];
            $contrasenia = $data['contrasenia'];
            $activo = $data['activo'];

            $result = $this->usuarioModel->updateUsuario($id, $usuario, $contrasenia, $activo);

            if ($result) {
                echo json_encode(['message' => 'Usuario actualizado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar usuario']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function deleteUsuario($id) {
        try {
            $result = $this->usuarioModel->deleteUsuario($id);

            if ($result) {
                echo json_encode(['message' => 'Usuario eliminado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar usuario']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}