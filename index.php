<?php

require_once(__DIR__ . '/controllers/UsuarioController.php');
require_once(__DIR__ . '/controllers/ComprobanteController.php');
require_once(__DIR__ . '/controllers/LoginController.php');
require_once(__DIR__ . '/auth/AuthMiddleware.php'); // Importa el middleware

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Obtén la URI de la solicitud
$uri = $_SERVER['REQUEST_URI'];

$uriSegments = explode('/', $uri);

if ($uriSegments[1] === 'apiPuntoVentas') {
    // Verifica la autenticación antes de llamar a cualquier controlador
    AuthMiddleware::checkAuth();

    if ($uriSegments[2] === 'usuarios') {
        $usuarioController = new UsuarioController();
        $usuarioController->handleRequest();
    } elseif ($uriSegments[2] === 'comprobantes') {
        $comprobanteController = new ComprobanteController();
        $comprobanteController->handleRequest();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}
