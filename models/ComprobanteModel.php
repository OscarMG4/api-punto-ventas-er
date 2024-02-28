<?php

require_once(__DIR__ . '/../database/Connection.php');

class ComprobanteModel {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getAllComprobantes() {
        try {
            $query = $this->conn->query('SELECT * FROM tb_comprobantes');
            return $query->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener todos los comprobantes: " . $e->getMessage());
        }
    }

    public function getComprobanteById($id) {
        try {
            $query = $this->conn->prepare('SELECT * FROM tb_comprobantes WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el comprobante por ID: " . $e->getMessage());
        }
    }

    public function createComprobante($codsunat, $nombre, $abreviatura, $venta, $compra, $facturacionElectronica): bool {
        try {
            $query = $this->conn->prepare('INSERT INTO tb_comprobantes (codsunat, nombre, abreviatura, venta, compra, facturacion_electronica) VALUES (:codsunat, :nombre, :abreviatura, :venta, :compra, :facturacion_electronica)');
            $query->bindParam(':codsunat', $codsunat, PDO::PARAM_STR);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->bindParam(':abreviatura', $abreviatura, PDO::PARAM_STR);
            $query->bindParam(':venta', $venta, PDO::PARAM_BOOL);
            $query->bindParam(':compra', $compra, PDO::PARAM_BOOL);
            $query->bindParam(':facturacion_electronica', $facturacionElectronica, PDO::PARAM_BOOL);
    
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al crear el comprobante: " . $e->getMessage());
        }
    }
    

    public function updateComprobante($id, $codsunat, $nombre, $abreviatura, $venta, $compra, $facturacionElectronica): bool {
        try {
            $query = $this->conn->prepare('UPDATE tb_comprobantes SET codsunat = :codsunat, nombre = :nombre, abreviatura = :abreviatura, venta = :venta, compra = :compra, facturacion_electronica = :facturacionElectronica WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':codsunat', $codsunat, PDO::PARAM_STR);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->bindParam(':abreviatura', $abreviatura, PDO::PARAM_STR);
            $query->bindParam(':venta', $venta, PDO::PARAM_BOOL);
            $query->bindParam(':compra', $compra, PDO::PARAM_BOOL);
            $query->bindParam(':facturacionElectronica', $facturacionElectronica, PDO::PARAM_BOOL);

            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar el comprobante: " . $e->getMessage());
        }
    }

    public function deleteComprobante($id): bool {
        try {
            $query = $this->conn->prepare('DELETE FROM tb_comprobantes WHERE id = :id');
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar el comprobante: " . $e->getMessage());
        }
    }
}
