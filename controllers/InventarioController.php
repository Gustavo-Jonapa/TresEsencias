
<?php
require_once 'models/Inventario.php';
require_once 'models/Proveedor.php';

class InventarioController {
    
    private function verificarAcceso() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo administradores.";
            header('Location: index.php');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAcceso();
        
        $inventarioModel = new Inventario();
        
        $items = $inventarioModel->obtenerTodos();
        
        $pageTitle = "Gestión de Inventario";
        include 'views/administrador/inventario/index.php';
    }
    
    public function crearItemInventario() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioModel = new Inventario();
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'cantidad' => floatval($_POST['cantidad']),
                'unidad_medida' => trim($_POST['unidad_medida']),
                'cantidad_minima' => floatval($_POST['cantidad_minima']),
                'precio_unitario' => floatval($_POST['precio_unitario']),
                'id_proveedor' => !empty($_POST['id_proveedor']) ? intval($_POST['id_proveedor']) : null
            ];
            
            $resultado = $inventarioModel->crear($datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Item agregado al inventario exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al crear el item";
            }
            
            header('Location: index.php?controller=administrador&action=inventario');
            exit();
        }
    }
    
    public function actualizarInventario() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioModel = new Inventario();
            
            $id = intval($_POST['id']);
            $cantidad = floatval($_POST['cantidad']);
            $tipo_movimiento = $_POST['tipo_movimiento']; // 'ENTRADA' o 'SALIDA'
            
            if ($cantidad <= 0) {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'La cantidad debe ser mayor a cero'
                ]);
                exit();
            }
            
            if (!in_array($tipo_movimiento, ['ENTRADA', 'SALIDA'])) {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Tipo de movimiento inválido'
                ]);
                exit();
            }
            
            $resultado = $inventarioModel->actualizarStock($id, $cantidad, $tipo_movimiento);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function obtenerDetalleItem() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $inventarioModel = new Inventario();
            $item = $inventarioModel->obtenerPorId($id);
            
            if ($item) {
                $item['VALOR_TOTAL'] = $item['CANTIDAD'] * $item['PRECIO_UNITARIO'];
                $item['ES_BAJO_STOCK'] = $item['CANTIDAD'] <= $item['CANTIDAD_MINIMA'];
                
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'OK',
                    'item' => $item
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Item no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function editarItemInventario() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioModel = new Inventario();
            
            $id = intval($_POST['id']);
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'cantidad' => floatval($_POST['cantidad']),
                'unidad_medida' => trim($_POST['unidad_medida']),
                'cantidad_minima' => floatval($_POST['cantidad_minima']),
                'precio_unitario' => floatval($_POST['precio_unitario']),
                'id_proveedor' => !empty($_POST['id_proveedor']) ? intval($_POST['id_proveedor']) : null
            ];
            
            $resultado = $inventarioModel->actualizar($id, $datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Item actualizado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al actualizar el item";
            }
            
            header('Location: index.php?controller=administrador&action=inventario');
            exit();
        }
    }
    
    public function eliminarItemInventario() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            
            $inventarioModel = new Inventario();
            $resultado = $inventarioModel->eliminar($id);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function obtenerItemsBajoStock() {
        $this->verificarAcceso();
        
        $inventarioModel = new Inventario();
        $items = $inventarioModel->obtenerItemsBajoStock();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'items' => $items,
            'total' => count($items)
        ]);
        exit();
    }
    
    public function obtenerProveedoresSelect() {
        $this->verificarAcceso();
        
        $proveedorModel = new Proveedor();
        $proveedores = $proveedorModel->obtenerActivos();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'proveedores' => $proveedores
        ]);
        exit();
    }
    
    public function exportarInventarioCSV() {
        $this->verificarAcceso();
        
        $inventarioModel = new Inventario();
        $items = $inventarioModel->obtenerTodos();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=inventario_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'ID',
            'Nombre',
            'Descripción',
            'Cantidad',
            'Unidad',
            'Stock Mínimo',
            'Precio Unitario',
            'Valor Total',
            'Estado'
        ]);
        
        foreach ($items as $item) {
            $valorTotal = $item['CANTIDAD'] * $item['PRECIO_UNITARIO'];
            $estado = $item['CANTIDAD'] <= $item['CANTIDAD_MINIMA'] ? 'BAJO' : 'ÓPTIMO';
            
            fputcsv($output, [
                $item['ID_INVENTARIO'],
                $item['NOMBRE'],
                $item['DESCRIPCION'],
                $item['CANTIDAD'],
                $item['UNIDAD_MEDIDA'],
                $item['CANTIDAD_MINIMA'],
                $item['PRECIO_UNITARIO'],
                $valorTotal,
                $estado
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>