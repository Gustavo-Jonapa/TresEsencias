<?php
require_once 'models/Calificacion.php';

class CalificacionController {
    public function index() {
        $pageTitle = "Calificaciones - Tres Esencias";
        
        $calificacionModel = new Calificacion();
        
        $estadisticas = $calificacionModel->obtenerPromedio();
        
        $calificacionesRecientes = $calificacionModel->obtenerRecientes(5);
        
        require_once "views/layouts/header.php";
        require_once "views/calificacion/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calificacion = $_POST['CALIFICACION'] ?? '';
            $tipo = $_POST['TIPO'] ?? '';
            $comentario = $_POST['COMENTARIO'] ?? '';
            $nombre = $_POST['NOMBRE'] ?? 'Anónimo';
            $email = $_POST['EMAIL'] ?? '';
            
            if (empty($calificacion) || empty($tipo) || empty($comentario)) {
                $_SESSION['error'] = "Por favor completa todos los campos obligatorios";
                header('Location: index.php?controller=calificacion');
                exit();
            }
            
            $calificacionModel = new Calificacion();
            $resultado = $calificacionModel->crear([
                'calificacion' => $calificacion,
                'tipo' => $tipo,
                'comentario' => $comentario,
                'nombre' => $nombre,
                'email' => $email
            ]);

            if ($resultado && isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "¡Gracias por tu calificación! Tu opinión es muy importante para nosotros.";
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al guardar la calificación";
            }
            
            header('Location: index.php?controller=calificacion');
            exit();
        }
    }
    
    public function ver() {
        $pageTitle = "Todas las Calificaciones - Tres Esencias";
        
        $calificacionModel = new Calificacion();
        $todasCalificaciones = $calificacionModel->obtenerTodas(50);
        $estadisticas = $calificacionModel->obtenerPromedio();
        
        require_once "views/layouts/header.php";
        require_once "views/calificacion/todas.php";
        require_once "views/layouts/footer.php";
    }
}
?>