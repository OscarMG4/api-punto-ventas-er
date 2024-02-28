<?php

class AuthMiddleware {
    public static function checkAuth() {
        session_start();

        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'Acceso no autorizado']);
            exit();
        }
    }
}
