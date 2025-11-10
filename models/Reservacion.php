<?php
require_once 'config/database.php';

class Reservacion {
    private $conn;
    private $table = "RESERVACIONES";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function crear($datos) {
        try {
            if (!$this->verificarDisponibilidad($datos['id_mesa'], $datos['fecha'], $datos['hora'])) {
                return ['Status' => 'ERROR', 'Mensaje' => 'La mesa no está disponible para esa fecha y hora'];
            }
            
            $sql = "{CALL SP_INSERT_RESERVACION(?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['id_cliente'], PDO::PARAM_INT);
            $stmt->bindParam(2, $datos['id_mesa'], PDO::PARAM_INT);
            $stmt->bindParam(3, $datos['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(4, $datos['hora'], PDO::PARAM_STR);
            $stmt->bindParam(5, $datos['personas'], PDO::PARAM_INT);
            $estado = $datos['estado'] ?? 'PENDIENTE';
            $stmt->bindParam(6, $estado, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Reservacion::crear - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function verificarDisponibilidad($idMesa, $fecha, $hora) {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM {$this->table}
                    WHERE ID_MESA = ?
                      AND FECHA = ?
                      AND HORA = ?
                      AND ESTADO IN ('PENDIENTE', 'CONFIRMADA')";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idMesa);
            $stmt->bindParam(2, $fecha);
            $stmt->bindParam(3, $hora);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] == 0;
        } catch(PDOException $e) {
            error_log("Error en Reservacion::verificarDisponibilidad - " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerMesas($fecha = null, $hora = null, $personas = null) {
        try {
            $sql = "{CALL SP_GET_MESAS_DISPONIBLES(?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $fecha);
            $stmt->bindParam(2, $hora);
            $stmt->bindParam(3, $personas);
            
            $stmt->execute();
            
            $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $resultado = [];
            foreach ($mesas as $mesa) {
                $disponible = $this->verificarDisponibilidad($mesa['ID_MESA'], $fecha, $hora);
                
                $resultado[] = [
                    'id' => $mesa['ID_MESA'],
                    'numero' => $mesa['NUMERO_MESA'],
                    'capacidad' => $mesa['CAPACIDAD'],
                    'estado' => $mesa['ESTADO'],
                    'disponible' => $disponible
                ];
            }
            
            return $resultado;
        } catch(PDOException $e) {
            error_log("Error en Reservacion::obtenerMesas - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerPorCliente($id_cliente) {
        try {
            $sql = "{CALL SP_SELECT_RESERVACIONES_CLIENTE(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id_cliente, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Reservacion::obtenerPorCliente - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerTodasReservaciones($fecha = null, $estado = null) {
        try {
            $sql = "{CALL SP_SELECT_RESERVACIONES(NULL, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $fecha);
            $stmt->bindParam(2, $estado);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Reservacion::obtenerTodasReservaciones - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_RESERVACIONES(?, NULL, NULL)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Reservacion::obtenerPorId - " . $e->getMessage());
            return null;
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            if (isset($datos['id_mesa']) && isset($datos['fecha']) && isset($datos['hora'])) {
                $sql = "SELECT COUNT(*) as total
                        FROM {$this->table}
                        WHERE ID_MESA = ?
                          AND FECHA = ?
                          AND HORA = ?
                          AND ID_RESERVACION != ?
                          AND ESTADO IN ('PENDIENTE', 'CONFIRMADA')";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $datos['id_mesa']);
                $stmt->bindParam(2, $datos['fecha']);
                $stmt->bindParam(3, $datos['hora']);
                $stmt->bindParam(4, $id);
                $stmt->execute();
                
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result['total'] > 0) {
                    return ['Status' => 'ERROR', 'Mensaje' => 'La mesa no está disponible para esa fecha y hora'];
                }
            }
            
            $sql = "{CALL SP_UPDATE_RESERVACION(?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $datos['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(3, $datos['hora'], PDO::PARAM_STR);
            $stmt->bindParam(4, $datos['personas'], PDO::PARAM_INT);
            $stmt->bindParam(5, $datos['id_mesa'], PDO::PARAM_INT);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Reservacion::actualizar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function cancelar($id) {
        try {
            $sql = "{CALL SP_CANCELAR_RESERVACION(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Reservacion::cancelar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function confirmar($id) {
        try {
            $sql = "UPDATE {$this->table} SET ESTADO = 'CONFIRMADA' WHERE ID_RESERVACION = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Reservación confirmada exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo confirmar la reservación'];
        } catch(PDOException $e) {
            error_log("Error en Reservacion::confirmar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function completar($id) {
        try {
            $sql = "UPDATE {$this->table} SET ESTADO = 'COMPLETADA' WHERE ID_RESERVACION = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Reservación completada exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo completar la reservación'];
        } catch(PDOException $e) {
            error_log("Error en Reservacion::completar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function obtenerReservacionesHoy() {
        try {
            $hoy = date('Y-m-d');
            return $this->obtenerTodasReservaciones($hoy, null);
        } catch(Exception $e) {
            error_log("Error en Reservacion::obtenerReservacionesHoy - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerEstadisticas($fecha = null) {
        try {
            if (!$fecha) {
                $fecha = date('Y-m-d');
            }
            
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN ESTADO = 'PENDIENTE' THEN 1 ELSE 0 END) as pendientes,
                        SUM(CASE WHEN ESTADO = 'CONFIRMADA' THEN 1 ELSE 0 END) as confirmadas,
                        SUM(CASE WHEN ESTADO = 'CANCELADA' THEN 1 ELSE 0 END) as canceladas,
                        SUM(CASE WHEN ESTADO = 'COMPLETADA' THEN 1 ELSE 0 END) as completadas,
                        SUM(PERSONAS) as total_personas
                    FROM {$this->table}
                    WHERE FECHA = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $fecha);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Reservacion::obtenerEstadisticas - " . $e->getMessage());
            return [
                'total' => 0,
                'pendientes' => 0,
                'confirmadas' => 0,
                'canceladas' => 0,
                'completadas' => 0,
                'total_personas' => 0
            ];
        }
    }
}
?>
