<?
require_once 'models/Empleado.php';

class EmpleadosController{
    public function index(){
        $pageTitle = "Empleados - Tres Esencias";

        require_once "views/layouts/header.php";
        require_once "views/empleados/index.php";
        require_once "views/layouts/footer.php";
    }
}
?>
<?php
require_once 'models/Empleado.php';

class EmpleadoController {
    
    private function verificarAcceso() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
            $_SESSION['error'] = "Acceso denegado. Solo administradores.";
            header('Location: index.php');
            exit();
        }
    }
    
    public function index() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        
        $empleados = $empleadoModel->obtenerTodos();
        
        $pageTitle = "Gestión de Empleados";
        include 'views/administrador/empleados/index.php';
    }
    
    public function crearEmpleado() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoModel = new Empleado();
            
            if (!empty($_POST['email'])) {
                if ($empleadoModel->existeEmail($_POST['email'])) {
                    $_SESSION['error'] = "El email ya está registrado para otro empleado";
                    header('Location: index.php?controller=administrador&action=empleados');
                    exit();
                }
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'puesto' => trim($_POST['puesto']),
                'telefono' => trim($_POST['telefono']),
                'email' => trim($_POST['email'] ?? ''),
                'salario' => floatval($_POST['salario']),
                'fecha_contratacion' => $_POST['fecha_contratacion'],
                'id_colonia' => !empty($_POST['id_colonia']) ? intval($_POST['id_colonia']) : 1
            ];
            
            $resultado = $empleadoModel->crear($datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Empleado registrado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al crear el empleado";
            }
            
            header('Location: index.php?controller=administrador&action=empleados');
            exit();
        }
    }
    
    public function editarEmpleado() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoModel = new Empleado();
            
            $id = intval($_POST['id']);
            
            if (!empty($_POST['email'])) {
                if ($empleadoModel->existeEmail($_POST['email'], $id)) {
                    $_SESSION['error'] = "El email ya está registrado para otro empleado";
                    header('Location: index.php?controller=administrador&action=empleados');
                    exit();
                }
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'puesto' => trim($_POST['puesto']),
                'telefono' => trim($_POST['telefono']),
                'email' => trim($_POST['email'] ?? ''),
                'salario' => floatval($_POST['salario']),
                'fecha_contratacion' => $_POST['fecha_contratacion'],
                'id_colonia' => !empty($_POST['id_colonia']) ? intval($_POST['id_colonia']) : 1
            ];
            
            $resultado = $empleadoModel->actualizar($id, $datos);
            
            if (isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "Empleado actualizado exitosamente";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al actualizar el empleado";
            }
            
            header('Location: index.php?controller=administrador&action=empleados');
            exit();
        }
    }
    
    public function obtenerDetalleEmpleado() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $empleadoModel = new Empleado();
            $empleado = $empleadoModel->obtenerPorId($id);
            
            if ($empleado) {
                $antiguedad = $empleadoModel->calcularAntiguedad($id);
                $empleado['ANTIGUEDAD'] = $antiguedad;
                
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'OK',
                    'empleado' => $empleado
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Empleado no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function obtenerEmpleadoParaEditar() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $empleadoModel = new Empleado();
            $empleado = $empleadoModel->obtenerPorId($id);
            
            header('Content-Type: application/json');
            if ($empleado) {
                echo json_encode([
                    'Status' => 'OK',
                    'empleado' => $empleado
                ]);
            } else {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'Empleado no encontrado'
                ]);
            }
            exit();
        }
    }
    
    public function eliminarEmpleado() {
        $this->verificarAcceso();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            
            $empleadoModel = new Empleado();
            $resultado = $empleadoModel->eliminar($id);
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit();
        }
    }
    
    public function buscarEmpleados() {
        $this->verificarAcceso();
        
        if (isset($_GET['q'])) {
            $termino = $_GET['q'];
            
            $empleadoModel = new Empleado();
            $empleados = $empleadoModel->buscar($termino);
            
            header('Content-Type: application/json');
            echo json_encode([
                'Status' => 'OK',
                'empleados' => $empleados,
                'total' => count($empleados)
            ]);
            exit();
        }
    }
    
    public function filtrarPorPuesto() {
        $this->verificarAcceso();
        
        if (isset($_GET['puesto'])) {
            $puesto = $_GET['puesto'];
            
            $empleadoModel = new Empleado();
            $empleados = $empleadoModel->obtenerPorPuesto($puesto);
            
            header('Content-Type: application/json');
            echo json_encode([
                'Status' => 'OK',
                'empleados' => $empleados,
                'total' => count($empleados)
            ]);
            exit();
        }
    }
    
    public function obtenerEstadisticasEmpleados() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        $estadisticas = $empleadoModel->obtenerEstadisticas();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'estadisticas' => $estadisticas
        ]);
        exit();
    }
    
    public function exportarEmpleadosCSV() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->obtenerTodos();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empleados_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'ID',
            'Nombre',
            'Apellido',
            'Nombre Completo',
            'Puesto',
            'Teléfono',
            'Email',
            'Salario',
            'Fecha Contratación'
        ]);
        
        foreach ($empleados as $empleado) {
            fputcsv($output, [
                $empleado['ID_EMPLEADO'],
                $empleado['NOMBRE'],
                $empleado['APELLIDO'],
                $empleado['NOMBRE'] . ' ' . $empleado['APELLIDO'],
                $empleado['PUESTO'],
                $empleado['TELEFONO'],
                $empleado['EMAIL'] ?? '',
                number_format($empleado['SALARIO'], 2),
                $empleado['FECHA_CONTRATACION']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    public function obtenerNominaPorPuesto() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        $nomina = $empleadoModel->obtenerNominaPorPuesto();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'nomina' => $nomina
        ]);
        exit();
    }
    
    public function obtenerPuestosUnicos() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        $puestos = $empleadoModel->obtenerPuestosUnicos();
        
        header('Content-Type: application/json');
        echo json_encode([
            'Status' => 'OK',
            'puestos' => $puestos
        ]);
        exit();
    }
    
    public function calcularAntiguedadEmpleado() {
        $this->verificarAcceso();
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            $empleadoModel = new Empleado();
            $antiguedad = $empleadoModel->calcularAntiguedad($id);
            
            header('Content-Type: application/json');
            if ($antiguedad) {
                echo json_encode([
                    'Status' => 'OK',
                    'antiguedad' => $antiguedad
                ]);
            } else {
                echo json_encode([
                    'Status' => 'ERROR',
                    'Mensaje' => 'No se pudo calcular la antigüedad'
                ]);
            }
            exit();
        }
    }
    
    public function validarEmailEmpleado() {
        $this->verificarAcceso();
        
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            $excluirId = isset($_GET['excluir']) ? intval($_GET['excluir']) : null;
            
            $empleadoModel = new Empleado();
            $existe = $empleadoModel->existeEmail($email, $excluirId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'Status' => 'OK',
                'existe' => $existe
            ]);
            exit();
        }
    }
    
    public function reporteNomina() {
        $this->verificarAcceso();
        
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->obtenerTodos();
        $estadisticas = $empleadoModel->obtenerEstadisticas();
        $nominaPorPuesto = $empleadoModel->obtenerNominaPorPuesto();
        
        $pageTitle = "Reporte de Nómina";
        include 'views/administrador/empleados/reporte_nomina.php';
    }
}
?>