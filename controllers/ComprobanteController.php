<?php

require_once(__DIR__ . '/../models/ComprobanteModel.php');

class ComprobanteController {
    private $comprobanteModel;

    public function __construct() {
        $this->comprobanteModel = new ComprobanteModel();
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
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if (preg_match('/^\/comprobantes\/(\d+)$/', $path, $matches)) {
            $this->getComprobanteById($matches[1]);
        } elseif ($path === '/comprobantes') {
            $this->getAllComprobantes();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function handlePostRequest() {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        if ($path === '/comprobantes') {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data) {
                $this->createComprobante($data);
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
    
        if (preg_match('/^\/comprobantes\/(\d+)$/', $path, $matches)) {
            $data = json_decode(file_get_contents('php://input'), true);
    
            if ($data) {
                $this->updateComprobante($matches[1], $data);
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

        if (preg_match('/^\/comprobantes\/(\d+)$/', $path, $matches)) {
            $this->deleteComprobante($matches[1]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
    }

    private function getAllComprobantes() {
        try {
            $comprobantes = $this->comprobanteModel->getAllComprobantes();
            header('Content-Type: application/json');
            echo json_encode($comprobantes);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function getComprobanteById($id) {
        try {
            $comprobante = $this->comprobanteModel->getComprobanteById($id);
            header('Content-Type: application/json');

            if ($comprobante) {
                echo json_encode($comprobante);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Comprobante no encontrado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function createComprobante($data) {
        try {
            $codsunat = $data['codsunat'];
            $nombre = $data['nombre'];
            $abreviatura = $data['abreviatura'];
            $venta = $data['venta'];
            $compra = $data['compra'];
            $facturacionElectronica = $data['facturacion_electronica'];

            $result = $this->comprobanteModel->createComprobante($codsunat, $nombre, $abreviatura, $venta, $compra, $facturacionElectronica);

            if ($result) {
                echo json_encode(['message' => 'Comprobante creado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear comprobante']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function updateComprobante($id, $data) {
        try {
            $codsunat = $data['codsunat'];
            $nombre = $data['nombre'];
            $abreviatura = $data['abreviatura'];
            $venta = $data['venta'];
            $compra = $data['compra'];
            $facturacionElectronica = $data['facturacion_electronica'];

            $result = $this->comprobanteModel->updateComprobante($id, $codsunat, $nombre, $abreviatura, $venta, $compra, $facturacionElectronica);

            if ($result) {
                echo json_encode(['message' => 'Comprobante actualizado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar comprobante']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function deleteComprobante($id) {
        try {
            $result = $this->comprobanteModel->deleteComprobante($id);

            if ($result) {
                echo json_encode(['message' => 'Comprobante eliminado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar comprobante']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

