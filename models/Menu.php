<?php
require_once 'config/database.php';

class Menu {
    private $conn;
    private $table = "MENU";
    private $uploadDir = "uploads/menu/";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    public function obtenerTodos() {
        try {
            $sql = "{CALL SP_SELECT_MENU()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Menu::obtenerTodos - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerPorTipo($tipo) {
        try {
            $sql = "{CALL SP_SELECT_MENU(NULL, ?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $tipo);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Menu::obtenerPorTipo - " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_MENU(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Menu::obtenerPorId - " . $e->getMessage());
            return null;
        }
    }
    
    public function crear($datos, $archivo = null) {
        try {
            $imagenRuta = null;
            if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
                $imagenRuta = $this->subirImagen($archivo);
                if (!$imagenRuta) {
                    return ['Status' => 'ERROR', 'Mensaje' => 'Error al subir la imagen'];
                }
            }
            
            $sql = "{CALL SP_INSERT_MENU(?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['descripcion']);
            $stmt->bindParam(3, $datos['precio']);
            $stmt->bindParam(4, $datos['tipo']);
            $disponible = $datos['disponible'] ?? 1;
            $stmt->bindParam(5, $disponible);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($imagenRuta && isset($result['ID_MENU'])) {
                $this->actualizarImagen($result['ID_MENU'], $imagenRuta);
            }
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Menu::crear - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizar($id, $datos, $archivo = null) {
        try {
            $imagenRuta = null;
            if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
                $platilloActual = $this->obtenerPorId($id);
                if ($platilloActual && !empty($platilloActual['IMAGEN_URL'])) {
                    $this->eliminarImagen($platilloActual['IMAGEN_URL']);
                }
                
                $imagenRuta = $this->subirImagen($archivo);
                if (!$imagenRuta) {
                    return ['Status' => 'ERROR', 'Mensaje' => 'Error al subir la imagen'];
                }
            }
            
            $sql = "{CALL SP_UPDATE_MENU(?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $datos['nombre']);
            $stmt->bindParam(3, $datos['descripcion']);
            $stmt->bindParam(4, $datos['precio']);
            $stmt->bindParam(5, $datos['tipo']);
            $disponible = $datos['disponible'] ?? 1;
            $stmt->bindParam(6, $disponible);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($imagenRuta) {
                $this->actualizarImagen($id, $imagenRuta);
            }
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Menu::actualizar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function eliminar($id) {
        try {
            $platillo = $this->obtenerPorId($id);
            
            $sql = "UPDATE {$this->table} SET DISPONIBLE = 0 WHERE ID_MENU = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            
            if ($stmt->execute()) {
                if ($platillo && !empty($platillo['IMAGEN_URL'])) {
                    $this->eliminarImagen($platillo['IMAGEN_URL']);
                }
                
                return ['Status' => 'OK', 'Mensaje' => 'Platillo eliminado exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo eliminar el platillo'];
        } catch(PDOException $e) {
            error_log("Error en Menu::eliminar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    private function subirImagen($archivo) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $nombreArchivo = $archivo['name'];
        $tmpName = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }
        
        if ($archivo['size'] > 5 * 1024 * 1024) {
            return false;
        }
        
        $nombreNuevo = uniqid('menu_') . '.' . $extension;
        $rutaDestino = $this->uploadDir . $nombreNuevo;
        
        if (move_uploaded_file($tmpName, $rutaDestino)) {
            return $rutaDestino;
        }
        
        return false;
    }
    
    private function eliminarImagen($ruta) {
        if (file_exists($ruta)) {
            return unlink($ruta);
        }
        return false;
    }
    
    private function actualizarImagen($idMenu, $rutaImagen) {
        try {
            $sql = "UPDATE {$this->table} SET IMAGEN_URL = ? WHERE ID_MENU = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $rutaImagen);
            $stmt->bindParam(2, $idMenu);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error en Menu::actualizarImagen - " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerDisponibles($tipo = null) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE DISPONIBLE = 1";
            
            if ($tipo) {
                $sql .= " AND TIPO = ?";
            }
            
            $sql .= " ORDER BY TIPO, NOMBRE";
            
            $stmt = $this->conn->prepare($sql);
            
            if ($tipo) {
                $stmt->bindParam(1, $tipo);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Menu::obtenerDisponibles - " . $e->getMessage());
            return [];
        }
    }
}
?>
