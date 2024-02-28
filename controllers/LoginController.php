<?php

require_once(__DIR__ . '/../auth/AuthMiddleware.php');
require_once(__DIR__ . '/../models/LoginModel.php');

class LoginController {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->handlePostRequest();
                break;
            default:
                http_response_code(405); // Método no permitido
                echo json_encode(['error' => 'Método no permitido']);
        }
    }

    private function handlePostRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if ($path === '/login') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data) {
                $this->login($data);
            } else {
                http_response_code(400); // Solicitud incorrecta
                echo json_encode(['error' => 'Datos de solicitud no válidos']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function login($data) {
        try {
            $usuario = $data['usuario'];
            $contrasenia = $data['contrasenia'];

            $result = $this->loginModel->validateLogin($usuario, $contrasenia);

            if ($result) {
                // Autenticación exitosa
                session_start();
                $_SESSION['authenticated'] = true;

                echo json_encode(['message' => 'Inicio de sesión exitoso']);
            } else {
                http_response_code(401); // No autorizado
                echo json_encode(['error' => 'Credenciales incorrectas']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
