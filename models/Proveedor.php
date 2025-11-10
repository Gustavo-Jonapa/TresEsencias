<?php
require_once 'config/database.php';

class Proveedor {
    private $conn;
    private $table = "PROVEEDORES";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerTodos() {
        try {
            $sql = "{CALL SP_SELECT_PROVEEDORES()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_PROVEEDORES(?)}";
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
            $sql = "{CALL SP_INSERT_PROVEEDOR(?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['contacto']);
            $stmt->bindParam(3, $datos['telefono']);
            $stmt->bindParam(4, $datos['email']);
            $stmt->bindParam(5, $datos['direccion']);
            $stmt->bindParam(6, $datos['tipo_producto']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $sql = "{CALL SP_UPDATE_PROVEEDOR(?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $datos['nombre']);
            $stmt->bindParam(3, $datos['contacto']);
            $stmt->bindParam(4, $datos['telefono']);
            $stmt->bindParam(5, $datos['email']);
            $stmt->bindParam(6, $datos['direccion']);
            $stmt->bindParam(7, $datos['tipo_producto']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function eliminar($id) {
        try {
            $sql = "{CALL SP_DELETE_PROVEEDOR(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function obtenerActivos() {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE ACTIVO = 1 ORDER BY NOMBRE";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_proveedores,
                        COUNT(CASE WHEN ACTIVO = 1 THEN 1 END) as activos,
                        COUNT(CASE WHEN ACTIVO = 0 THEN 1 END) as inactivos,
                        COUNT(DISTINCT TIPO_PRODUCTO) as tipos_producto
                    FROM {$this->table}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_proveedores' => $result['total_proveedores'] ?? 0,
                'activos' => $result['activos'] ?? 0,
                'inactivos' => $result['inactivos'] ?? 0,
                'tipos_producto' => $result['tipos_producto'] ?? 0
            ];
        } catch(PDOException $e) {
            return [
                'total_proveedores' => 0,
                'activos' => 0,
                'inactivos' => 0,
                'tipos_producto' => 0
            ];
        }
    }
    
    public function buscarPorTipo($tipo) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE TIPO_PRODUCTO LIKE ? 
                    ORDER BY NOMBRE";
            
            $stmt = $this->conn->prepare($sql);
            $tipoBusqueda = "%{$tipo}%";
            $stmt->bindParam(1, $tipoBusqueda);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function desactivar($id) {
        try {
            $sql = "UPDATE {$this->table} SET ACTIVO = 0 WHERE ID_PROVEEDOR = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Proveedor desactivado exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo desactivar el proveedor'];
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function activar($id) {
        try {
            $sql = "UPDATE {$this->table} SET ACTIVO = 1 WHERE ID_PROVEEDOR = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Proveedor activado exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo activar el proveedor'];
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE NOMBRE LIKE ? 
                       OR CONTACTO LIKE ? 
                       OR TIPO_PRODUCTO LIKE ?
                    ORDER BY NOMBRE";
            
            $stmt = $this->conn->prepare($sql);
            $busqueda = "%{$termino}%";
            $stmt->bindParam(1, $busqueda);
            $stmt->bindParam(2, $busqueda);
            $stmt->bindParam(3, $busqueda);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
}
?>