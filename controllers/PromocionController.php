<?php
require_once 'models/Promocion.php';

class PromocionController {
    public function index() {
        $pageTitle = "Promociones - Tres Esencias";
        
        $promocionModel = new Promocion();
        $promociones = $promocionModel->obtenerActivas();
        
        if (empty($promociones)) {
            $promociones = $promocionModel->obtenerTodas();
        }
        
        require_once "views/layouts/header.php";
        require_once "views/promociones/index.php";
        require_once "views/layouts/footer.php";
    }
}
?>