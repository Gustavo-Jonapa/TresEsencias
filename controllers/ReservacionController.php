<?php
require_once 'models/Reservacion.php';
require_once 'models/Cliente.php';

class ReservacionController {
    public function index() {
        $pageTitle = "Reservaciones - Tres Esencias";
        
        $reservacionModel = new Reservacion();
        $mesas = $reservacionModel->obtenerMesas();
        
        require_once "views/layouts/header.php";
        require_once "views/reservaciones/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                $_SESSION['error'] = "Debe iniciar sesión para hacer una reservación";
                header('Location: index.php?controller=auth&action=login');
                exit();
            }
            
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $personas = $_POST['personas'] ?? '';
            $mesa_id = $_POST['mesa_id'] ?? '';
            
            if (empty($fecha) || empty($hora) || empty($personas) || empty($mesa_id)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header('Location: index.php?controller=reservacion');
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->crear([
                'id_cliente' => $_SESSION['usuario_id'],
                'id_mesa' => $mesa_id,
                'fecha' => $fecha,
                'hora' => $hora,
                'personas' => $personas,
                'estado' => 'PENDIENTE'
            ]);
            
            if ($resultado && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Reservación creada exitosamente. Se enviará confirmación por email.";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al crear la reservación";
            }
            
            header('Location: index.php?controller=reservacion');
            exit();
        }
    }
    
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit();
            }
            
            $reservacion_id = $_POST['reservacion_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $personas = $_POST['personas'] ?? '';
            
            if (empty($reservacion_id) || empty($fecha) || empty($hora) || empty($personas)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->actualizar($reservacion_id, [
                'fecha' => $fecha,
                'hora' => $hora,
                'personas' => $personas,
                'id_mesa' => $_POST['mesa_id'] ?? null
            ]);
            
            if ($resultado && $resultado['Status'] === 'OK') {
                echo json_encode(['success' => true, 'message' => 'Reservación actualizada']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultado['Mensaje'] ?? 'Error']);
            }
            exit();
        }
    }
    
    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit();
            }
            
            $reservacion_id = $_POST['reservacion_id'] ?? '';
            
            if (empty($reservacion_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de reservación no válido']);
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->cancelar($reservacion_id);
            
            if ($resultado && $resultado['Status'] === 'OK') {
                echo json_encode(['success' => true, 'message' => 'Reservación cancelada']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultado['Mensaje'] ?? 'Error']);
            }
            exit();
        }
    }
    public function verificarDisponibilidad() {
        header('Content-Type: application/json');
        
        $idMesa = $_GET['mesa'] ?? null;
        $fecha = $_GET['fecha'] ?? null;
        $hora = $_GET['hora'] ?? null;
        
        if (!$idMesa || !$fecha || !$hora) {
            echo json_encode(['disponible' => false, 'mensaje' => 'Datos incompletos']);
            exit();
        }
        
        $reservacionModel = new Reservacion();
        $disponible = $reservacionModel->verificarDisponibilidad($idMesa, $fecha, $hora);
        
        echo json_encode(['disponible' => $disponible]);
        exit();
    }
}
?>