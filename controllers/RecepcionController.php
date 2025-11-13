<?php
require_once 'models/Cliente.php';
require_once 'models/Reservacion.php';
require_once 'models/Recepcion.php';

class RecepcionController {
    
    private function verificarAccesoRecepcion() {
        if (!isset($_SESSION['es_recepcion']) || $_SESSION['es_recepcion'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo personal de recepción.";
            header('Location: index.php?controller=inicio');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Panel de Recepción - Tres Esencias";
        
        $reservacionModel = new Reservacion();
        $reservacionesHoy = $reservacionModel->obtenerReservacionesHoy();
        
        $recepcionModel = new Recepcion();
        $estadisticas = $recepcionModel->obtenerEstadisticasDia();
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function clientes() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Clientes - Recepción";
        
        $clienteModel = new Cliente();
        $clientes = $clienteModel->obtenerTodos();
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/clientes.php";
        require_once "views/layouts/footer.php";
    }
    
    public function reservaciones() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Reservaciones - Recepción";
        
        $reservacionModel = new Reservacion();
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $estado = $_GET['estado'] ?? null;
        
        $reservaciones = $reservacionModel->obtenerTodasReservaciones($fecha, $estado);
        $estadisticas = $reservacionModel->obtenerEstadisticas($fecha);
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/reservaciones.php";
        require_once "views/layouts/footer.php";
    }
    
    public function mesas() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Mesas - Recepción";
        
        $reservacionModel = new Reservacion();
        $mesas = $reservacionModel->obtenerMesas();
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/mesas.php";
        require_once "views/layouts/footer.php";
    }
    
    public function registrarCliente() {
        $this->verificarAccesoRecepcion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $email = trim($_POST['email'] ?? '');
            
            if (empty($nombre) || empty($telefono)) {
                $_SESSION['error'] = "Nombre y teléfono son obligatorios";
                header('Location: index.php?controller=recepcion&action=clientes');
                exit();
            }
            
            $clienteModel = new Cliente();
            $resultado = $clienteModel->crear([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'email' => $email
            ]);
            
            if ($resultado && isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Cliente registrado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al registrar cliente";
            }
            
            header('Location: index.php?controller=recepcion&action=clientes');
            exit();
        }
    }
    
    public function crearReservacion() {
        $this->verificarAccesoRecepcion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id = $_POST['cliente_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $personas = $_POST['personas'] ?? '';
            $mesa_id = $_POST['mesa_id'] ?? '';
            
            if (empty($cliente_id) || empty($fecha) || empty($hora) || empty($personas) || empty($mesa_id)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header('Location: index.php?controller=recepcion&action=reservaciones');
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->crear([
                'id_cliente' => $cliente_id,
                'id_mesa' => $mesa_id,
                'fecha' => $fecha,
                'hora' => $hora,
                'personas' => $personas,
                'estado' => 'CONFIRMADA'
            ]);
            
            if ($resultado && isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Reservación creada exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al crear la reservación";
            }
            
            header('Location: index.php?controller=recepcion&action=reservaciones');
            exit();
        }
    }
    
    public function buscarCliente() {
        $this->verificarAccesoRecepcion();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $busqueda = trim($_POST['busqueda'] ?? '');
            
            if (empty($busqueda)) {
                echo json_encode(['success' => false, 'mensaje' => 'Término de búsqueda vacío']);
                exit();
            }
            
            $clienteModel = new Cliente();
            $resultados = $clienteModel->buscar($busqueda);
            
            echo json_encode([
                'success' => true,
                'resultados' => $resultados
            ]);
            exit();
        }
        
        echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
        exit();
    }
    
    public function confirmarReservacion() {
        $this->verificarAccesoRecepcion();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservacion_id = $_POST['reservacion_id'] ?? '';
            
            if (empty($reservacion_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de reservación no válido']);
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->confirmar($reservacion_id);
            
            if ($resultado && $resultado['Status'] === 'OK') {
                echo json_encode(['success' => true, 'message' => 'Reservación confirmada']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultado['Mensaje'] ?? 'Error']);
            }
            exit();
        }
    }
    
    public function completarReservacion() {
        $this->verificarAccesoRecepcion();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservacion_id = $_POST['reservacion_id'] ?? '';
            
            if (empty($reservacion_id)) {
                echo json_encode(['success' => false, 'message' => 'ID de reservación no válido']);
                exit();
            }
            
            $reservacionModel = new Reservacion();
            $resultado = $reservacionModel->completar($reservacion_id);
            
            if ($resultado && $resultado['Status'] === 'OK') {
                echo json_encode(['success' => true, 'message' => 'Reservación completada']);
            } else {
                echo json_encode(['success' => false, 'message' => $resultado['Mensaje'] ?? 'Error']);
            }
            exit();
        }
    }
    
    public function obtenerClientes() {
        $this->verificarAccesoRecepcion();
        
        header('Content-Type: application/json');
        
        $clienteModel = new Cliente();
        $clientes = $clienteModel->obtenerTodos();
        
        echo json_encode([
            'success' => true,
            'clientes' => $clientes
        ]);
        exit();
    }
}
?>