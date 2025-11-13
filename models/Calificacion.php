<?php
require_once 'config/database.php';

class Calificacion {
    private $conn;
    private $table = "CALIFICACIONES";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function crear($datos) {
        try {
            $sql = "{CALL SP_INSERT_CALIFICACION(?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['calificacion'], PDO::PARAM_INT);
            $stmt->bindParam(2, $datos['tipo'], PDO::PARAM_STR);
            $stmt->bindParam(3, $datos['comentario'], PDO::PARAM_STR);
            $stmt->bindParam(4, $datos['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(5, $datos['email'], PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Calificacion::crear - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => 'Error al guardar la calificación: ' . $e->getMessage()];
        }
    }
    public function obtenerTodas($limite = 10) {
        try {
            $sql = "{CALL SP_SELECT_CALIFICACIONES(NULL, NULL, NULL, ?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerTodas - " . $e->getMessage());
            return [];
        }
    }
    public function obtenerPromedio() {
        try {
            $sql = "{CALL SP_GET_PROMEDIO_CALIFICACIONES()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado || $resultado['TOTAL_CALIFICACIONES'] == 0) {
                return [
                    'PROMEDIO_CALIFICACION' => 0,
                    'TOTAL_CALIFICACIONES' => 0,
                    'CINCO_ESTRELLAS' => 0,
                    'CUATRO_ESTRELLAS' => 0,
                    'TRES_ESTRELLAS' => 0,
                    'DOS_ESTRELLAS' => 0,
                    'UNA_ESTRELLA' => 0
                ];
            }
            
            return $resultado;
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerPromedio - " . $e->getMessage());
            return [
                'PROMEDIO_CALIFICACION' => 0,
                'TOTAL_CALIFICACIONES' => 0,
                'CINCO_ESTRELLAS' => 0,
                'CUATRO_ESTRELLAS' => 0,
                'TRES_ESTRELLAS' => 0,
                'DOS_ESTRELLAS' => 0,
                'UNA_ESTRELLA' => 0
            ];
        }
    }
    public function obtenerPorTipo($tipo) {
        try {
            $sql = "{CALL SP_SELECT_CALIFICACIONES(NULL, ?, NULL, NULL)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $tipo, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerPorTipo - " . $e->getMessage());
            return [];
        }
    }
    public function obtenerRecientes($limite = 5) {
        try {
            $sql = "{CALL SP_SELECT_CALIFICACIONES(NULL, NULL, NULL, ?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerRecientes con SP - " . $e->getMessage());
            try {
                $sql = "SELECT TOP (?) 
                            NOMBRE,
                            CALIFICACION,
                            COMENTARIO,
                            TIPO,
                            EMAIL,
                            FECHA_REGISTRO
                        FROM {$this->table}
                        ORDER BY FECHA_REGISTRO DESC";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $limite, PDO::PARAM_INT);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e2) {
                error_log("Error en Calificacion::obtenerRecientes fallback - " . $e2->getMessage());
                return [];
            }
        }
    }
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_CALIFICACIONES(?, NULL, NULL, NULL)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerPorId - " . $e->getMessage());
            return null;
        }
    }
    public function obtenerPorCalificacion($calificacion) {
        try {
            $sql = "{CALL SP_SELECT_CALIFICACIONES(NULL, NULL, ?, NULL)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $calificacion, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerPorCalificacion - " . $e->getMessage());
            return [];
        }
    }
    public function obtenerEstadisticasPorTipo() {
        try {
            $sql = "SELECT 
                        TIPO,
                        COUNT(*) as TOTAL,
                        AVG(CAST(CALIFICACION AS FLOAT)) as PROMEDIO
                    FROM {$this->table}
                    GROUP BY TIPO
                    ORDER BY TOTAL DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerEstadisticasPorTipo - " . $e->getMessage());
            return [];
        }
    }
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_CALIFICACION = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Calificación eliminada exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo eliminar la calificación'];
        } catch(PDOException $e) {
            error_log("Error en Calificacion::eliminar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
}
?>