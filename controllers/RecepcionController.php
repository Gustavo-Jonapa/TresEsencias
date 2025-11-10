<?php
require_once 'models/Cliente.php';
require_once 'models/Reservacion.php';

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
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function clientes() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Clientes - Recepción";
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/clientes.php";
        require_once "views/layouts/footer.php";
    }
    
    public function reservaciones() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Reservaciones - Recepción";
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/reservaciones.php";
        require_once "views/layouts/footer.php";
    }
    
    public function mesas() {
        $this->verificarAccesoRecepcion();
        
        $pageTitle = "Mesas - Recepción";
        
        require_once "views/layouts/header.php";
        require_once "views/recepcion/mesas.php";
        require_once "views/layouts/footer.php";
    }
    
    public function registrarCliente() {
        $this->verificarAccesoRecepcion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (empty($nombre) || empty($telefono)) {
                $_SESSION['error'] = "Nombre y teléfono son obligatorios";
                header('Location: index.php?controller=recepcion');
                exit();
            }
            $cliente = new Cliente();
            $resultado = $cliente->crear([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'email' => $email
            ]);
            
            $_SESSION['mensaje'] = "Cliente registrado exitosamente";
            header('Location: index.php?controller=recepcion');
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
            $notas = $_POST['notas'] ?? '';
            
            if (empty($cliente_id) || empty($fecha) || empty($hora) || empty($personas) || empty($mesa_id)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header('Location: index.php?controller=recepcion');
                exit();
            }
            $reservacion = new Reservacion();
            $resultado = $reservacion->crear([
            ]);
            
            $_SESSION['mensaje'] = "Reservación creada exitosamente";
            header('Location: index.php?controller=recepcion');
            exit();
        }
    }
    
    public function buscarCliente() {
        $this->verificarAccesoRecepcion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $busqueda = $_POST['busqueda'] ?? '';
            
            $cliente = new Cliente();
            $resultados = $cliente->buscar($busqueda);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'resultados' => []
            ]);
            exit();
        }
    }
}
?>
