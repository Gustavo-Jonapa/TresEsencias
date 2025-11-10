<?php
require_once 'models/Empleado.php';
require_once 'models/Inventario.php';
require_once 'models/Producto.php';
require_once 'models/Proveedor.php';

class AdministradorController {
    
    private function verificarAccesoAdmin() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo personal administrativo.";
            header('Location: index.php?controller=inicio');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAccesoAdmin();
        
        $pageTitle = "Panel de Administración - Tres Esencias";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/index.php";
        require_once "views/layouts/footer.php";
    }

    public function empleados() {
        $this->verificarAccesoAdmin();
        
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->obtenerTodos();
        
        $pageTitle = "Empleados - Panel Admin";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/empleados/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crearEmpleado() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoModel = new Empleado();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'puesto' => $_POST['puesto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'salario' => $_POST['salario'] ?? 0,
                'fecha_contratacion' => $_POST['fecha_contratacion'] ?? date('Y-m-d'),
                'id_colonia' => $_POST['id_colonia'] ?? 1
            ];
            
            $resultado = $empleadoModel->crear($datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Empleado registrado exitosamente";
            } else {
                $_SESSION['error'] = "Error al registrar empleado: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=empleados');
            exit();
        }
    }
    
    public function editarEmpleado() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoModel = new Empleado();
            
            $id = $_POST['id'] ?? 0;
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'puesto' => $_POST['puesto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'salario' => $_POST['salario'] ?? 0,
                'fecha_contratacion' => $_POST['fecha_contratacion'] ?? date('Y-m-d'),
                'id_colonia' => $_POST['id_colonia'] ?? 1
            ];
            
            $resultado = $empleadoModel->actualizar($id, $datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Empleado actualizado exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar empleado: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=empleados');
            exit();
        }
    }
    
    public function eliminarEmpleado() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoModel = new Empleado();
            $id = $_POST['id'] ?? 0;
            
            $resultado = $empleadoModel->eliminar($id);
            
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function inventario() {
        $this->verificarAccesoAdmin();
        
        $inventarioModel = new Inventario();
        $items = $inventarioModel->obtenerTodos();
        
        $pageTitle = "Inventario - Panel Admin";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/inventario/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crearItemInventario() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioModel = new Inventario();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'cantidad' => $_POST['cantidad'] ?? 0,
                'unidad_medida' => $_POST['unidad_medida'] ?? '',
                'cantidad_minima' => $_POST['cantidad_minima'] ?? 0,
                'precio_unitario' => $_POST['precio_unitario'] ?? 0,
                'id_proveedor' => $_POST['id_proveedor'] ?? null
            ];
            
            $resultado = $inventarioModel->crear($datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Item agregado al inventario exitosamente";
            } else {
                $_SESSION['error'] = "Error al agregar item: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=inventario');
            exit();
        }
    }
    
    public function actualizarInventario() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioModel = new Inventario();
            
            $id = $_POST['id'] ?? 0;
            $cantidad = $_POST['cantidad'] ?? 0;
            $tipo_movimiento = $_POST['tipo_movimiento'] ?? 'ENTRADA';
            
            $resultado = $inventarioModel->actualizarStock($id, $cantidad, $tipo_movimiento);
            
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function productos() {
        $this->verificarAccesoAdmin();
        
        $productoModel = new Producto();
        $productos = $productoModel->obtenerTodos();
        
        $pageTitle = "Productos - Panel Admin";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/productos/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crearProducto() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoModel = new Producto();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio_compra' => $_POST['precio_compra'] ?? 0,
                'precio_venta' => $_POST['precio_venta'] ?? 0,
                'stock_actual' => $_POST['stock_actual'] ?? 0,
                'stock_minimo' => $_POST['stock_minimo'] ?? 0,
                'id_proveedor' => $_POST['id_proveedor'] ?? null
            ];
            
            $resultado = $productoModel->crear($datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Producto registrado exitosamente";
            } else {
                $_SESSION['error'] = "Error al registrar producto: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=productos');
            exit();
        }
    }
    
    public function proveedores() {
        $this->verificarAccesoAdmin();
        
        $proveedorModel = new Proveedor();
        $proveedores = $proveedorModel->obtenerTodos();
        
        $pageTitle = "Proveedores - Panel Admin";
        
        require_once "views/layouts/header.php";
        require_once "views/administrador/proveedores/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function crearProveedor() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proveedorModel = new Proveedor();
            
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'tipo_producto' => $_POST['tipo_producto'] ?? ''
            ];
            
            $resultado = $proveedorModel->crear($datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Proveedor registrado exitosamente";
            } else {
                $_SESSION['error'] = "Error al registrar proveedor: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=proveedores');
            exit();
        }
    }
    
    public function editarProveedor() {
        $this->verificarAccesoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proveedorModel = new Proveedor();
            
            $id = $_POST['id'] ?? 0;
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'tipo_producto' => $_POST['tipo_producto'] ?? ''
            ];
            
            $resultado = $proveedorModel->actualizar($id, $datos);
            
            if ($resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Proveedor actualizado exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar proveedor: " . $resultado['Mensaje'];
            }
            
            header('Location: index.php?controller=administrador&action=proveedores');
            exit();
        }
    }
}
?>