<?php
require_once 'config/database.php';

class Inventario {
    private $conn;
    private $table = "INVENTARIO";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerTodos() {
        try {
            $sql = "{CALL SP_SELECT_INVENTARIO()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_INVENTARIO(?)}";
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
            $sql = "{CALL SP_INSERT_INVENTARIO(?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['descripcion']);
            $stmt->bindParam(3, $datos['cantidad']);
            $stmt->bindParam(4, $datos['unidad_medida']);
            $stmt->bindParam(5, $datos['cantidad_minima']);
            $stmt->bindParam(6, $datos['precio_unitario']);
            $stmt->bindParam(7, $datos['id_proveedor']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $sql = "{CALL SP_UPDATE_INVENTARIO(?, ?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $datos['nombre']);
            $stmt->bindParam(3, $datos['descripcion']);
            $stmt->bindParam(4, $datos['cantidad']);
            $stmt->bindParam(5, $datos['unidad_medida']);
            $stmt->bindParam(6, $datos['cantidad_minima']);
            $stmt->bindParam(7, $datos['precio_unitario']);
            $stmt->bindParam(8, $datos['id_proveedor']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizarStock($id, $cantidad, $tipo_movimiento = 'ENTRADA') {
        try {
            $sql = "{CALL SP_ACTUALIZAR_STOCK_INVENTARIO(?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $cantidad);
            $stmt->bindParam(3, $tipo_movimiento);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function obtenerItemsBajoStock() {
        try {
            $sql = "{CALL SP_ITEMS_BAJO_STOCK()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function eliminar($id) {
        try {
            $sql = "{CALL SP_DELETE_INVENTARIO(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_items,
                        SUM(CANTIDAD * PRECIO_UNITARIO) as valor_total,
                        COUNT(CASE WHEN CANTIDAD <= CANTIDAD_MINIMA THEN 1 END) as items_bajo_stock
                    FROM {$this->table}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_items' => $result['total_items'] ?? 0,
                'valor_total' => $result['valor_total'] ?? 0,
                'items_bajo_stock' => $result['items_bajo_stock'] ?? 0
            ];
        } catch(PDOException $e) {
            return [
                'total_items' => 0,
                'valor_total' => 0,
                'items_bajo_stock' => 0
            ];
        }
    }
}
?>