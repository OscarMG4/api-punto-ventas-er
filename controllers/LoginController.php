<?php

require_once(__DIR__ . '/../models/UsuarioModel.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use \Firebase\JWT\JWT;

class LoginController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['usuario']) || !isset($data['contrasenia'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Credenciales no válidas']);
            return;
        }

        $usuario = $data['usuario'];
        $contrasenia = $data['contrasenia'];

        // Obtener usuario por credenciales
        $user = $this->usuarioModel->getUsuarioByCredentials($usuario, $contrasenia);

        if ($user && $user['activo']) {
            // Generar token JWT
            $token = $this->generateJWT($user['id']);
            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales incorrectas o usuario inactivo']);
        }
    }

    private function generateJWT($userId)
    {
        $key = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
        $issuedAt = time();
        $expirationTime = $issuedAt + 60 * 60; // 1 hora de validez

        $token = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'userId' => $userId,
            ],
        ];

        return JWT::encode($token, $key, 'HS256');
    }
}
