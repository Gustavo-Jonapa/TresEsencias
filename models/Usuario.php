<?php
require_once 'config/database.php';

class Usuario {
    private $conn;
    private $table = "LOGIN_USUARIOS";
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function crear($datos) {
        try {
            $sql = "{CALL SP_REGISTRAR_USUARIO_CLIENTE(?, ?, ?, ?, ?, ?)}";
            $stmt = $this->conn->prepare($sql);
            $passwordHash = hash('sha256', $datos['password']);
            
            $stmt->bindParam(1, $datos['nombre']);
            $stmt->bindParam(2, $datos['apellido']);
            $stmt->bindParam(3, $datos['telefono']);
            $stmt->bindParam(4, $datos['email']);
            $stmt->bindParam(5, $datos['usuario']);
            $stmt->bindParam(6, $passwordHash);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Usuario::crear - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function autenticar($usuario, $password) {
        try {
            $passwordHash = hash('sha256', $password);
            
            $sql = "SELECT 
                        L.ID_LOGIN,
                        L.ID_CLIENTE,
                        L.USUARIO,
                        L.TIPO_USUARIO,
                        L.ACTIVO,
                        C.NOMBRE,
                        C.APELLIDO,
                        C.EMAIL,
                        C.TELEFONO
                    FROM LOGIN_USUARIOS L
                    INNER JOIN CLIENTES C ON L.ID_CLIENTE = C.ID_CLIENTE
                    WHERE L.USUARIO = ? 
                      AND L.PASSWORD = ?
                      AND L.ACTIVO = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $usuario);
            $stmt->bindParam(2, $passwordHash);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($result['TIPO_USUARIO'] === 'ADMIN' || $result['TIPO_USUARIO'] === 'RECEPCION') {
                    $sqlEmpleado = "SELECT ID_EMPLEADO, NOMBRE, APELLIDO, PUESTO
                                    FROM EMPLEADOS
                                    WHERE EMAIL = ?";
                    $stmtEmpleado = $this->conn->prepare($sqlEmpleado);
                    $stmtEmpleado->bindParam(1, $result['EMAIL']);
                    $stmtEmpleado->execute();
                    $empleado = $stmtEmpleado->fetch(PDO::FETCH_ASSOC);
                    
                    if ($empleado) {
                        $result['ID_EMPLEADO'] = $empleado['ID_EMPLEADO'];
                        $result['PUESTO'] = $empleado['PUESTO'];
                    }
                }
                
                $result['Status'] = 'OK';
                $result['Mensaje'] = 'Autenticación exitosa';
                return $result;
            } else {
                return ['Status' => 'ERROR', 'Mensaje' => 'Usuario o contraseña incorrectos'];
            }
            
        } catch(PDOException $e) {
            error_log("Error en Usuario::autenticar - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function autenticarRecepcion($usuario, $password) {
        try {
            $sql = "{CALL SP_AUTENTICAR_RECEPCION(?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $usuario);
            $stmt->bindParam(2, $password);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Usuario::autenticarRecepcion - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function autenticarAdministrador($usuario, $password) {
        try {
            $sql = "{CALL SP_AUTENTICAR_ADMIN(?, ?)}";
            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam(1, $usuario);
            $stmt->bindParam(2, $password);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error en Usuario::autenticarAdministrador - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function verificarUsuarioExiste($usuario) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE USUARIO = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $usuario);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch(PDOException $e) {
            error_log("Error en Usuario::verificarUsuarioExiste - " . $e->getMessage());
            return false;
        }
    }
    public function verificarEmailExiste($email) {
        try {
            $query = "SELECT COUNT(*) as total FROM CLIENTES WHERE EMAIL = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch(PDOException $e) {
            error_log("Error en Usuario::verificarEmailExiste - " . $e->getMessage());
            return false;
        }
    }
    public function cambiarPassword($idLogin, $passwordNueva) {
        try {
            $passwordHash = hash('sha256', $passwordNueva);
            
            $sql = "UPDATE LOGIN_USUARIOS SET PASSWORD = ? WHERE ID_LOGIN = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $passwordHash);
            $stmt->bindParam(2, $idLogin);
            
            if ($stmt->execute()) {
                return ['Status' => 'OK', 'Mensaje' => 'Contraseña actualizada exitosamente'];
            }
            
            return ['Status' => 'ERROR', 'Mensaje' => 'No se pudo actualizar la contraseña'];
        } catch(PDOException $e) {
            error_log("Error en Usuario::cambiarPassword - " . $e->getMessage());
            return ['Status' => 'ERROR', 'Mensaje' => $e->getMessage()];
        }
    }
    public function obtenerPorId($idLogin) {
        try {
            $sql = "SELECT 
                        L.*,
                        C.NOMBRE,
                        C.APELLIDO,
                        C.EMAIL,
                        C.TELEFONO
                    FROM LOGIN_USUARIOS L
                    INNER JOIN CLIENTES C ON L.ID_CLIENTE = C.ID_CLIENTE
                    WHERE L.ID_LOGIN = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idLogin);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Usuario::obtenerPorId - " . $e->getMessage());
            return null;
        }
    }
}
?>
