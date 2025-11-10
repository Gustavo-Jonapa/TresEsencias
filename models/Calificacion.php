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
            
            if (!$resultado) {
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
            $sql = "SELECT TOP (?) 
                        NOMBRE,
                        CALIFICACION,
                        COMENTARIO,
                        TIPO,
                        FECHA_REGISTRO
                    FROM {$this->table}
                    ORDER BY FECHA_REGISTRO DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Calificacion::obtenerRecientes - " . $e->getMessage());
            return [];
        }
    }
}
?>
