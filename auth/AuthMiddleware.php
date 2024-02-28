<?php

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public function checkToken()
    {
        $headers = apache_request_headers();

        if (isset($headers['Authorization']) && !empty($headers['Authorization'])) {
            $token = $this->extractToken($headers['Authorization']);

            if ($this->verifyToken($token)) {
                return true;
            }
        }

        http_response_code(401);
        echo json_encode(['error' => 'Token no válido']);
        exit();
    }

    private function extractToken($authorizationHeader)
    {
        return trim(str_replace('Bearer', '', $authorizationHeader));
    }

    private function verifyToken($token)
    {
        $key = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
    
        try {
            JWT::decode($token, new Key($key, 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
