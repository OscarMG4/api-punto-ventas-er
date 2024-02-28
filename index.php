<?php

require_once(__DIR__ . '/controllers/UsuarioController.php');
require_once(__DIR__ . '/controllers/ComprobanteController.php');
require_once(__DIR__ . '/controllers/LoginController.php');
require_once(__DIR__ . '/auth/AuthMiddleware.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$uri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', $uri);

if ($uriSegments[1] === 'apiPuntoVentas') {
    if ($uriSegments[2] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $loginController = new LoginController();
        $loginController->login();
    } elseif ($uriSegments[2] === 'usuarios' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->checkToken();

        $usuarioController = new UsuarioController();
        $usuarioController->handleRequest();
    } elseif ($uriSegments[2] === 'comprobantes') {
        // Aplicar middleware de autenticación a todas las rutas de comprobantes
        //$authMiddleware = new AuthMiddleware();
        //$authMiddleware->checkToken();

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
