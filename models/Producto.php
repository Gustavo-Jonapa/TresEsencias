<?php
require_once 'config/database.php';

class Producto {
    private $conn;
    private $table = "PRODUCTOS";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function obtenerTodos() {
        try {
            $sql = "{CALL SP_SELECT_PRODUCTOS()}";
            $stmt = $this->conn->prepare($sql); 
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $sql = "{CALL SP_SELECT_PRODUCTOS(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }
    
    public function crear($datos) {
        try {
            $sql = "{CALL SP_INSERT_PRODUCTO(?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['descripcion']);
            $stmt->bindParam(3, $datos['precio_compra']);
            $stmt->bindParam(4, $datos['precio_venta']);
            $stmt->bindParam(5, $datos['stock_actual']);
            $stmt->bindParam(6, $datos['stock_minimo']);
            $stmt->bindParam(7, $datos['id_proveedor']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $sql = "{CALL SP_UPDATE_PRODUCTO(?, ?, ?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $datos['nombre']);
            $stmt->bindParam(3, $datos['descripcion']);
            $stmt->bindParam(4, $datos['precio_compra']);
            $stmt->bindParam(5, $datos['precio_venta']);
            $stmt->bindParam(6, $datos['stock_actual']);
            $stmt->bindParam(7, $datos['stock_minimo']);
            $stmt->bindParam(8, $datos['id_proveedor']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function eliminar($id) {
        try {
            $sql = "{CALL SP_DELETE_PRODUCTO(?)}";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function obtenerProductosBajoStock() {
        try {
            $sql = "{CALL SP_PRODUCTOS_BAJO_STOCK()}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_productos,
                        SUM(STOCK_ACTUAL) as stock_total,
                        COUNT(CASE WHEN STOCK_ACTUAL <= STOCK_MINIMO THEN 1 END) as productos_bajo_stock,
                        SUM(STOCK_ACTUAL * PRECIO_COMPRA) as valor_inventario_compra,
                        SUM(STOCK_ACTUAL * PRECIO_VENTA) as valor_inventario_venta,
                        SUM((PRECIO_VENTA - PRECIO_COMPRA) * STOCK_ACTUAL) as utilidad_potencial,
                        AVG(PRECIO_VENTA - PRECIO_COMPRA) as margen_promedio
                    FROM {$this->table}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_productos' => $result['total_productos'] ?? 0,
                'stock_total' => $result['stock_total'] ?? 0,
                'productos_bajo_stock' => $result['productos_bajo_stock'] ?? 0,
                'valor_inventario_compra' => $result['valor_inventario_compra'] ?? 0,
                'valor_inventario_venta' => $result['valor_inventario_venta'] ?? 0,
                'utilidad_potencial' => $result['utilidad_potencial'] ?? 0,
                'margen_promedio' => $result['margen_promedio'] ?? 0
            ];
        } catch(PDOException $e) {
            return [
                'total_productos' => 0,
                'stock_total' => 0,
                'productos_bajo_stock' => 0,
                'valor_inventario_compra' => 0,
                'valor_inventario_venta' => 0,
                'utilidad_potencial' => 0,
                'margen_promedio' => 0
            ];
        }
    }
    
    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE NOMBRE LIKE ? 
                       OR DESCRIPCION LIKE ?
                    ORDER BY NOMBRE";
            
            $stmt = $this->conn->prepare($sql);
            $busqueda = "%{$termino}%";
            $stmt->bindParam(1, $busqueda);
            $stmt->bindParam(2, $busqueda);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function actualizarStock($id, $cantidad, $operacion = 'SUMAR') {
        try {
            $producto = $this->obtenerPorId($id);
            if (!$producto) {
                return ['Status' => 'ERROR', 'Mensaje' => 'Producto no encontrado'];
            }
            
            $nuevoStock = $producto['STOCK_ACTUAL'];
            
            if ($operacion === 'SUMAR') {
                $nuevoStock += $cantidad;
            } elseif ($operacion === 'RESTAR') {
                $nuevoStock -= $cantidad;
                
                if ($nuevoStock < 0) {
                    return ['Status' => 'ERROR', 'Mensaje' => 'Stock insuficiente'];
                }
            }
            
            $sql = "UPDATE {$this->table} SET STOCK_ACTUAL = ? WHERE ID_PRODUCTO = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $nuevoStock);
            $stmt->bindParam(2, $id);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Stock actualizado exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'Error al actualizar el stock'];
        } catch(PDOException $e) {
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    
    public function obtenerPorProveedor($id_proveedor) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE ID_PROVEEDOR = ? 
                    ORDER BY NOMBRE";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id_proveedor);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function calcularMargen($id) {
        try {
            $producto = $this->obtenerPorId($id);
            
            if (!$producto) {
                return null;
            }
            
            $precioCompra = floatval($producto['PRECIO_COMPRA']);
            $precioVenta = floatval($producto['PRECIO_VENTA']);
            
            $margenUnitario = $precioVenta - $precioCompra;
            $porcentajeMargen = 0;
            
            if ($precioCompra > 0) {
                $porcentajeMargen = ($margenUnitario / $precioCompra) * 100;
            }
            
            $utilidadPorStock = $margenUnitario * $producto['STOCK_ACTUAL'];
            
            return [
                'margen_unitario' => $margenUnitario,
                'porcentaje_margen' => $porcentajeMargen,
                'utilidad_por_stock' => $utilidadPorStock,
                'precio_compra' => $precioCompra,
                'precio_venta' => $precioVenta,
                'stock_actual' => $producto['STOCK_ACTUAL']
            ];
        } catch(Exception $e) {
            return null;
        }
    }
    
    public function obtenerMasRentables($limite = 10) {
        try {
            $sql = "SELECT *,
                           (PRECIO_VENTA - PRECIO_COMPRA) as MARGEN_UNITARIO,
                           ((PRECIO_VENTA - PRECIO_COMPRA) / PRECIO_COMPRA * 100) as PORCENTAJE_MARGEN,
                           ((PRECIO_VENTA - PRECIO_COMPRA) * STOCK_ACTUAL) as UTILIDAD_POTENCIAL
                    FROM {$this->table}
                    WHERE PRECIO_COMPRA > 0
                    ORDER BY PORCENTAJE_MARGEN DESC
                    LIMIT ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function obtenerPorRangoPrecio($min, $max, $tipoPrecio = 'VENTA') {
        try {
            $campo = $tipoPrecio === 'COMPRA' ? 'PRECIO_COMPRA' : 'PRECIO_VENTA';
            
            $sql = "SELECT * FROM {$this->table} 
                    WHERE {$campo} BETWEEN ? AND ? 
                    ORDER BY {$campo}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $min);
            $stmt->bindParam(2, $max);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    public function existeNombre($nombre, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE NOMBRE = ?";
            
            if ($excluirId) {
                $sql .= " AND ID_PRODUCTO != ?";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $nombre);
            
            if ($excluirId) {
                $stmt->bindParam(2, $excluirId);
            }
            
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function obtenerAnalisisRentabilidad() {
        try {
            $sql = "SELECT 
                        NOMBRE,
                        PRECIO_COMPRA,
                        PRECIO_VENTA,
                        STOCK_ACTUAL,
                        (PRECIO_VENTA - PRECIO_COMPRA) as MARGEN_UNITARIO,
                        ((PRECIO_VENTA - PRECIO_COMPRA) / PRECIO_COMPRA * 100) as PORCENTAJE_MARGEN,
                        ((PRECIO_VENTA - PRECIO_COMPRA) * STOCK_ACTUAL) as UTILIDAD_POTENCIAL,
                        (STOCK_ACTUAL * PRECIO_COMPRA) as INVERSION_STOCK,
                        (STOCK_ACTUAL * PRECIO_VENTA) as VALOR_VENTA_STOCK
                    FROM {$this->table}
                    WHERE PRECIO_COMPRA > 0
                    ORDER BY PORCENTAJE_MARGEN DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
}
?>