<?php
require_once 'config/database.php';

class Promocion {
    private $conn;
    private $table = "PROMOCIONES";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerTodas() {
        try {
            $sql = "{CALL SP_SELECT_PROMOCIONES()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerActivas() {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE GETDATE() BETWEEN FECHA_INICIO AND FECHA_FIN ORDER BY FECHA_INICIO DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_PROMOCIONES(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }
    
    public function crear($datos) {
        try {
            $sql = "{CALL SP_INSERT_PROMOCION(?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['descripcion']);
            $stmt->bindParam(2, $datos['descuento_porcentaje']);
            $stmt->bindParam(3, $datos['fecha_inicio']);
            $stmt->bindParam(4, $datos['fecha_fin']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
}
?>
