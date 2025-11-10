<?php
require_once 'models/Calificacion.php';

class CalificacionController {
    public function index() {
        $pageTitle = "Calificaciones - Tres Esencias";
        
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

            $_SESSION['mensaje'] = "¡Gracias por tu calificación! Tu opinión es muy importante para nosotros.";
            header('Location: index.php?controller=calificacion');
            exit();
        }
    }
}
?>
