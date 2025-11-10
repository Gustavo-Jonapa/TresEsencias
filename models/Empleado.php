<?php
require_once 'config/database.php';

class Empleado {
    private $conn;
    private $table = "EMPLEADOS";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerTodos() {
        try {
            $sql = "{CALL SP_SELECT_EMPLEADOS()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_EMPLEADOS(?)}";
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
            $sql = "{CALL SP_INSERT_EMPLEADO(?, ?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['apellido']);
            $stmt->bindParam(3, $datos['puesto']);
            $stmt->bindParam(4, $datos['telefono']);
            $stmt->bindParam(5, $datos['email']);
            $stmt->bindParam(6, $datos['salario']);
            $stmt->bindParam(7, $datos['fecha_contratacion']);
            $stmt->bindParam(8, $datos['id_colonia']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $sql = "{CALL SP_UPDATE_EMPLEADO(?, ?, ?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $datos['nombre']);
            $stmt->bindParam(3, $datos['apellido']);
            $stmt->bindParam(4, $datos['puesto']);
            $stmt->bindParam(5, $datos['telefono']);
            $stmt->bindParam(6, $datos['email']);
            $stmt->bindParam(7, $datos['salario']);
            $stmt->bindParam(8, $datos['fecha_contratacion']);
            $stmt->bindParam(9, $datos['id_colonia']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function eliminar($id) {
        try {
            $sql = "{CALL SP_DELETE_EMPLEADO(?)}";
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
                        COUNT(*) as total_empleados,
                        COUNT(DISTINCT PUESTO) as puestos_diferentes,
                        SUM(SALARIO) as nomina_total,
                        AVG(SALARIO) as salario_promedio,
                        MIN(FECHA_CONTRATACION) as empleado_mas_antiguo,
                        MAX(FECHA_CONTRATACION) as empleado_mas_reciente
                    FROM {$this->table}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_empleados' => $result['total_empleados'] ?? 0,
                'puestos_diferentes' => $result['puestos_diferentes'] ?? 0,
                'nomina_total' => $result['nomina_total'] ?? 0,
                'salario_promedio' => $result['salario_promedio'] ?? 0,
                'empleado_mas_antiguo' => $result['empleado_mas_antiguo'] ?? null,
                'empleado_mas_reciente' => $result['empleado_mas_reciente'] ?? null
            ];
        } catch(PDOException $e) {
            return [
                'total_empleados' => 0,
                'puestos_diferentes' => 0,
                'nomina_total' => 0,
                'salario_promedio' => 0,
                'empleado_mas_antiguo' => null,
                'empleado_mas_reciente' => null
            ];
        }
    }
    
    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE NOMBRE LIKE ? 
                       OR APELLIDO LIKE ? 
                       OR PUESTO LIKE ?
                       OR CONCAT(NOMBRE, ' ', APELLIDO) LIKE ?
                    ORDER BY NOMBRE, APELLIDO";
            
            $stmt = $this->conn->prepare($sql);
            $busqueda = "%{$termino}%";
            $stmt->bindParam(1, $busqueda);
            $stmt->bindParam(2, $busqueda);
            $stmt->bindParam(3, $busqueda);
            $stmt->bindParam(4, $busqueda);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorPuesto($puesto) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE PUESTO = ? 
                    ORDER BY NOMBRE, APELLIDO";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $puesto);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPuestosUnicos() {
        try {
            $sql = "SELECT DISTINCT PUESTO 
                    FROM {$this->table} 
                    WHERE PUESTO IS NOT NULL 
                    ORDER BY PUESTO";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorRangoSalario($min, $max) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE SALARIO BETWEEN ? AND ? 
                    ORDER BY SALARIO DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $min);
            $stmt->bindParam(2, $max);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function calcularAntiguedad($id) {
        try {
            $empleado = $this->obtenerPorId($id);
            
            if (!$empleado || !isset($empleado['FECHA_CONTRATACION'])) {
                return null;
            }
            
            $fechaContratacion = new DateTime($empleado['FECHA_CONTRATACION']);
            $fechaActual = new DateTime();
            $diferencia = $fechaActual->diff($fechaContratacion);
            
            return [
                'años' => $diferencia->y,
                'meses' => $diferencia->m,
                'dias' => $diferencia->d,
                'total_dias' => $diferencia->days,
                'fecha_contratacion' => $empleado['FECHA_CONTRATACION']
            ];
        } catch(Exception $e) {
            return null;
        }
    }
    
    public function obtenerPorRangoFechas($fechaInicio, $fechaFin) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE FECHA_CONTRATACION BETWEEN ? AND ? 
                    ORDER BY FECHA_CONTRATACION DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $fechaInicio);
            $stmt->bindParam(2, $fechaFin);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function existeEmail($email, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE EMAIL = ?";
            
            if ($excluirId) {
                $sql .= " AND ID_EMPLEADO != ?";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $email);
            
            if ($excluirId) {
                $stmt->bindParam(2, $excluirId);
            }
            
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function obtenerNominaPorPuesto() {
        try {
            $sql = "SELECT 
                        PUESTO,
                        COUNT(*) as cantidad_empleados,
                        SUM(SALARIO) as nomina_total,
                        AVG(SALARIO) as salario_promedio,
                        MIN(SALARIO) as salario_minimo,
                        MAX(SALARIO) as salario_maximo
                    FROM {$this->table}
                    GROUP BY PUESTO
                    ORDER BY nomina_total DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
}
?>