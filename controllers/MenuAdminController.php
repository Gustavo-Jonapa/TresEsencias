<?php
require_once 'models/Menu.php';

class MenuAdminController {
    
    private function verificarAccesoAdmin() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo personal administrativo.";
            header('Location: index.php?controller=inicio');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAccesoAdmin();
        
        $menuModel = new Menu();
        $platillos = $menuModel->obtenerTodos();
        
        $pageTitle = "Gestión de Menú - Panel Admin";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/menu/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crear() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuModel = new Menu();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'tipo' => $_POST['tipo'] ?? '',
                'disponible' => isset($_POST['disponible']) ? 1 : 0
            ];
            
            $archivo = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;
            
            $resultado = $menuModel->crear($datos, $archivo);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Platillo agregado exitosamente";
            } else {
                $_SESSION['error'] = "Error al agregar platillo: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=menuAdmin');
            exit();
        }
    }
    
    public function obtenerParaEditar() {
        $this->verificarAccesoAdmin();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $menuModel = new Menu();
            $platillo = $menuModel->obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($platillo) {
                echo json_encode([
                    'Status' => 'OK',
                    'platillo' => $platillo
                ]);
            } else {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Platillo no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function actualizar() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuModel = new Menu();
            
            $id = $_POST['id'] ?? 0;
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'tipo' => $_POST['tipo'] ?? '',
                'disponible' => isset($_POST['disponible']) ? 1 : 0
            ];
            
            $archivo = isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['imagen'] : null;
            
            $resultado = $menuModel->actualizar($id, $datos, $archivo);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Platillo actualizado exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar platillo: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=menuAdmin');
            exit();
        }
    }
    
    public function eliminar() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            
            $menuModel = new Menu();
            $resultado = $menuModel->eliminar($id);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function verDetalle() {
        $this->verificarAccesoAdmin();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $menuModel = new Menu();
            $platillo = $menuModel->obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($platillo) {
                echo json_encode([
                    'Status' => 'OK',
                    'platillo' => $platillo
                ]);
            } else {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Platillo no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function cambiarDisponibilidad() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $disponible = intval($_POST['disponible'] ?? 0);
            
            $menuModel = new Menu();
            $platillo = $menuModel->obtenerPorId($id);
            
            if ($platillo) {
                $datos = [
                    'nombre' => $platillo['NOMBRE'],
                    'descripcion' => $platillo['DESCRIPCION'],
                    'precio' => $platillo['PRECIO'],
                    'tipo' => $platillo['TIPO'],
                    'disponible' => $disponible
                ];
                
                $resultado = $menuModel->actualizar($id, $datos);
                
                header('Content-Type: application/json');
                echo json_encode($resultado);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['Status' => 'ERROR', 'Mensaje' => 'Platillo no encontrado']);
            }
            exit();
        }
    }
    
    public function filtrar() {
        $this->verificarAccesoAdmin();
        
        $tipo = $_GET['tipo'] ?? null;
        $disponible = isset($_GET['disponible']) ? intval($_GET['disponible']) : null;
        
        $menuModel = new Menu();
        
        if ($disponible !== null && $disponible === 1) {
            $platillos = $menuModel->obtenerDisponibles($tipo);
        } else if ($tipo) {
            $platillos = $menuModel->obtenerPorTipo($tipo);
        } else {
            $platillos = $menuModel->obtenerTodos();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'platillos' => $platillos,
            'total' => count($platillos)
        ]);
        exit();
    }
}
?>
