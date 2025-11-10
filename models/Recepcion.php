<?php
require_once 'config/database.php';

class Recepcion {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerEstadisticasDia() {
        try {
            $sql = "{CALL SP_GET_ESTADISTICAS_DIA()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [
                'RESERVACIONES_HOY' => 0,
                'MESAS_OCUPADAS' => 0,
                'TOTAL_MESAS' => 0,
                'CLIENTES_REGISTRADOS' => 0,
                'RESERVACIONES_PENDIENTES' => 0,
                'INGRESOS_HOY' => 0
            ];
        }
    }
}
?>
