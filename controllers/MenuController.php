<?php
require_once 'models/Platillo.php';

class MenuController {
    public function index() {
        $pageTitle = "Menú - Tres Esencias";
        
        $platilloModel = new Platillo();
        $platillos = $platilloModel->obtenerTodos();
        
        require_once "views/layouts/header.php";
        require_once "views/menu/index.php";
        require_once "views/layouts/footer.php";
    }
    
    public function categoria() {
        $categoria = $_GET['cat'] ?? 'platos';
        
        $categoriaMap = [
            'platos' => 'Platos Fuertes',
            'postres' => 'Postres',
            'bebidas' => 'Bebidas'
        ];
        
        $categoriaNombre = $categoriaMap[$categoria] ?? 'Platos Fuertes';
        $pageTitle = $categoriaNombre . " - Tres Esencias";
        
        $platilloModel = new Platillo();
        $platillos = $platilloModel->obtenerPorTipo($categoriaNombre);
        
        require_once "views/layouts/header.php";
        require_once "views/menu/categoria.php";
        require_once "views/layouts/footer.php";
    }
}
?>