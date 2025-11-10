<?
require_once 'models/Producto.php';

class ProductosController{
    public function index(){
        $pageTitle = "Productos - Tres Esencias";

        require_once "views/layouts/header.php";
        require_once "views/productos/index.php";
        require_once "views/layouts/footer.php";
    }
}
?>
<?php
require_once 'models/Producto.php';
require_once 'models/Proveedor.php';

class ProductoController {
    
    private function verificarAcceso() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo administradores.";
            header('Location: index.php');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAcceso();
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerTodos();
        
        $pageTitle = "Gestión de Productos";
        include 'views/administrador/productos/index.php';
    }
    
    public function crearProducto() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoModel = new Producto();
            
            if ($productoModel->existeNombre($_POST['nombre'])) {
                $_SESSION['error'] = "Ya existe un producto con ese nombre";
                header('Location: index.php?controller=administrador&action=productos');
                exit();
            }
            
            if (floatval($_POST['precio_venta']) < floatval($_POST['precio_compra'])) {
                $_SESSION['error'] = "El precio de venta debe ser mayor al precio de compra";
                header('Location: index.php?controller=administrador&action=productos');
                exit();
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'precio_compra' => floatval($_POST['precio_compra']),
                'precio_venta' => floatval($_POST['precio_venta']),
                'stock_actual' => intval($_POST['stock_actual']),
                'stock_minimo' => intval($_POST['stock_minimo']),
                'id_proveedor' => !empty($_POST['id_proveedor']) ? intval($_POST['id_proveedor']) : null
            ];
            
            $resultado = $productoModel->crear($datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Producto registrado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al crear el producto";
            }
            
            header('Location: index.php?controller=administrador&action=productos');
            exit();
        }
    }
    
    public function editarProducto() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoModel = new Producto();
            
            $id = intval($_POST['id']);
            
            if ($productoModel->existeNombre($_POST['nombre'], $id)) {
                $_SESSION['error'] = "Ya existe otro producto con ese nombre";
                header('Location: index.php?controller=administrador&action=productos');
                exit();
            }
            
            if (floatval($_POST['precio_venta']) < floatval($_POST['precio_compra'])) {
                $_SESSION['error'] = "El precio de venta debe ser mayor al precio de compra";
                header('Location: index.php?controller=administrador&action=productos');
                exit();
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'precio_compra' => floatval($_POST['precio_compra']),
                'precio_venta' => floatval($_POST['precio_venta']),
                'stock_actual' => intval($_POST['stock_actual']),
                'stock_minimo' => intval($_POST['stock_minimo']),
                'id_proveedor' => !empty($_POST['id_proveedor']) ? intval($_POST['id_proveedor']) : null
            ];
            
            $resultado = $productoModel->actualizar($id, $datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Producto actualizado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al actualizar el producto";
            }
            
            header('Location: index.php?controller=administrador&action=productos');
            exit();
        }
    }
    
    public function obtenerDetalleProducto() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $productoModel = new Producto();
            $producto = $productoModel->obtenerPorId($id);
            
            if ($producto) {
                $analisisMargen = $productoModel->calcularMargen($id);
                $producto['ANALISIS_MARGEN'] = $analisisMargen;
                
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'OK',
                    'producto' => $producto
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Producto no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function obtenerProductoParaEditar() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $productoModel = new Producto();
            $producto = $productoModel->obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($producto) {
                echo json_encode([
                    'Status' => 'OK',
                    'producto' => $producto
                ]);
            } else {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Producto no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function eliminarProducto() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            
            $productoModel = new Producto();
            $resultado = $productoModel->eliminar($id);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function actualizarStockProducto() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $cantidad = floatval($_POST['cantidad']);
            $operacion = $_POST['operacion'];
            
            if ($cantidad <= 0) {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'La cantidad debe ser mayor a cero'
                ]);
                exit();
            }
            
            if (!in_array($operacion, ['SUMAR', 'RESTAR'])) {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Operación inválida'
                ]);
                exit();
            }
            
            $productoModel = new Producto();
            $resultado = $productoModel->actualizarStock($id, $cantidad, $operacion);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function buscarProductos() {
        $this->verificarAcceso();
        
        if (isset($_GET['q'])) {
            $termino = $_GET['q'];
            
            $productoModel = new Producto();
            $productos = $productoModel->buscar($termino);
            
            header('Content-Type: application/json');
            echo json_encode([
                'Status' => 'OK',
                'productos' => $productos,
                'total' => count($productos)
            ]);
            exit();
        }
    }
    
    public function obtenerProductosBajoStock() {
        $this->verificarAcceso();
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerProductosBajoStock();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'productos' => $productos,
            'total' => count($productos)
        ]);
        exit();
    }
    
    public function obtenerEstadisticasProductos() {
        $this->verificarAcceso();
        
        $productoModel = new Producto();
        $estadisticas = $productoModel->obtenerEstadisticas();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'estadisticas' => $estadisticas
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
    
    public function analisisRentabilidad() {
        $this->verificarAcceso();
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerAnalisisRentabilidad();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'productos' => $productos
        ]);
        exit();
    }
    
    public function productosMasRentables() {
        $this->verificarAcceso();
        
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerMasRentables($limite);
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'productos' => $productos
        ]);
        exit();
    }
    
    public function exportarProductosCSV() {
        $this->verificarAcceso();
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerTodos();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=productos_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'ID',
            'Nombre',
            'Descripción',
            'Precio Compra',
            'Precio Venta',
            'Margen Unitario',
            '% Margen',
            'Stock Actual',
            'Stock Mínimo',
            'Valor Stock Compra',
            'Valor Stock Venta',
            'Utilidad Potencial',
            'Estado'
        ]);
        
        foreach ($productos as $producto) {
            $precioCompra = floatval($producto['PRECIO_COMPRA']);
            $precioVenta = floatval($producto['PRECIO_VENTA']);
            $stockActual = intval($producto['STOCK_ACTUAL']);
            $stockMinimo = intval($producto['STOCK_MINIMO']);
            
            $margenUnitario = $precioVenta - $precioCompra;
            $porcentajeMargen = $precioCompra > 0 ? ($margenUnitario / $precioCompra) * 100 : 0;
            $valorStockCompra = $stockActual * $precioCompra;
            $valorStockVenta = $stockActual * $precioVenta;
            $utilidadPotencial = $margenUnitario * $stockActual;
            $estado = $stockActual <= $stockMinimo ? 'BAJO' : 'OK';
            
            fputcsv($output, [
                $producto['ID_PRODUCTO'],
                $producto['NOMBRE'],
                $producto['DESCRIPCION'] ?? '',
                number_format($precioCompra, 2),
                number_format($precioVenta, 2),
                number_format($margenUnitario, 2),
                number_format($porcentajeMargen, 2),
                $stockActual,
                $stockMinimo,
                number_format($valorStockCompra, 2),
                number_format($valorStockVenta, 2),
                number_format($utilidadPotencial, 2),
                $estado
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    public function validarNombreProducto() {
        $this->verificarAcceso();
        
        if (isset($_GET['nombre'])) {
            $nombre = $_GET['nombre'];
            $excluirId = isset($_GET['excluir']) ? intval($_GET['excluir']) : null;
            
            $productoModel = new Producto();
            $existe = $productoModel->existeNombre($nombre, $excluirId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'Status' => 'OK',
                'existe' => $existe
            ]);
            exit();
        }
    }
}
?>