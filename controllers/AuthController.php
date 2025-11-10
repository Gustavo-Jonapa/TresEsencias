<?php
require_once 'models/Usuario.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            if (empty($usuario) || empty($password)) {
                $_SESSION['error'] = "Usuario y contraseña son obligatorios";
                header('Location: index.php?controller=auth&action=login');
                exit();
            }
            
            $usuarioModel = new Usuario();
            $resultado = $usuarioModel->autenticar($usuario, $password);
            
            if ($resultado && isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                session_regenerate_id(true);
                
                $_SESSION['usuario_id'] = $resultado['ID_CLIENTE'];
                $_SESSION['usuario_nombre'] = $resultado['NOMBRE'];
                $_SESSION['usuario_apellido'] = $resultado['APELLIDO'];
                $_SESSION['usuario_email'] = $resultado['EMAIL'];
                $_SESSION['tipo_usuario'] = $resultado['TIPO_USUARIO'];
                
                switch ($resultado['TIPO_USUARIO']) {
                    case 'ADMIN':
                        $_SESSION['es_admin'] = true;
                        $_SESSION['es_recepcion'] = false;
                        $_SESSION['admin_id'] = $resultado['ID_EMPLEADO'] ?? $resultado['ID_CLIENTE'];
                        $_SESSION['admin_nombre'] = $resultado['NOMBRE'];
                        $_SESSION['mensaje'] = "Bienvenido al panel de administración, " . $resultado['NOMBRE'];
                        header('Location: index.php?controller=administrador');
                        break;
                        
                    case 'RECEPCION':
                        $_SESSION['es_recepcion'] = true;
                        $_SESSION['es_admin'] = false;
                        $_SESSION['recepcion_id'] = $resultado['ID_EMPLEADO'] ?? $resultado['ID_CLIENTE'];
                        $_SESSION['recepcion_nombre'] = $resultado['NOMBRE'];
                        $_SESSION['mensaje'] = "Bienvenido al panel de recepción, " . $resultado['NOMBRE'];
                        header('Location: index.php?controller=recepcion');
                        break;
                        
                    case 'CLIENTE':
                    default:
                        $_SESSION['es_admin'] = false;
                        $_SESSION['es_recepcion'] = false;
                        $_SESSION['mensaje'] = "¡Bienvenido " . $resultado['NOMBRE'] . "!";
                        header('Location: index.php?controller=inicio');
                        break;
                }
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Credenciales incorrectas";
                header('Location: index.php?controller=auth&action=login');
            }
            exit();
        }
        
        $pageTitle = "Iniciar Sesión - Tres Esencias";
        require_once "views/layouts/header.php";
        require_once "views/autentificacion/login.php";
        require_once "views/layouts/footer.php";
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');
            
            if (empty($nombre) || empty($email) || empty($telefono) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header('Location: index.php?controller=auth&action=register');
                exit();
            }
            
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Las contraseñas no coinciden";
                header('Location: index.php?controller=auth&action=register');
                exit();
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres";
                header('Location: index.php?controller=auth&action=register');
                exit();
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "El formato del email no es válido";
                header('Location: index.php?controller=auth&action=register');
                exit();
            }
            
            $nombrePartes = explode(' ', $nombre, 2);
            $nombrePrimero = $nombrePartes[0];
            $apellido = $nombrePartes[1] ?? '';
            
            $usuario = strtolower(explode('@', $email)[0]);
            
            $usuarioModel = new Usuario();
            if ($usuarioModel->verificarUsuarioExiste($usuario)) {
                $_SESSION['error'] = "Ya existe un usuario con ese email. Por favor usa otro.";
                header('Location: index.php?controller=auth&action=register');
                exit();
            }
            
            $resultado = $usuarioModel->crear([
                'nombre' => $nombrePrimero,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'email' => $email,
                'usuario' => $usuario,
                'password' => $password
            ]);
            
            if ($resultado && isset($resultado['Status']) && $resultado['Status'] === 'OK') {
                $_SESSION['mensaje'] = "¡Registro exitoso! Ya puedes iniciar sesión con tu email: " . $usuario;
                header('Location: index.php?controller=auth&action=login');
            } else {
                $_SESSION['error'] = $resultado['Mensaje'] ?? "Error al registrar usuario";
                header('Location: index.php?controller=auth&action=register');
            }
            exit();
        }
        
        $pageTitle = "Registrarse - Tres Esencias";
        require_once "views/layouts/header.php";
        require_once "views/autentificacion/register.php";
        require_once "views/layouts/footer.php";
    }
    
    public function mostrarLoginRecepcion() {
        $pageTitle = "Acceso Recepción - Tres Esencias";
        require_once "views/layouts/header.php";
        require_once "views/autentificacion/login_recepcion.php";
        require_once "views/layouts/footer.php";
    }
    
    public function loginRecepcion() {
        $_POST['tipo'] = 'RECEPCION';
        $this->login();
    }
    
    public function mostrarLoginAdministrador() {
        $pageTitle = "Acceso Administrador - Tres Esencias";
        require_once "views/layouts/header.php";
        require_once "views/autentificacion/login_administrador.php";
        require_once "views/layouts/footer.php";
    }
    
    public function loginAdministrador() {
        $_POST['tipo'] = 'ADMIN';
        $this->login();
    }
    
    public function logout() {
        $esRecepcion = isset($_SESSION['es_recepcion']) && $_SESSION['es_recepcion'] === true;
        $esAdmin = isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true;
        
        session_unset();
        session_destroy();
        
        session_start();
        $_SESSION['mensaje'] = "Sesión cerrada exitosamente";
        
        if ($esRecepcion) {
            header('Location: index.php?controller=auth&action=mostrarLoginRecepcion');
        } elseif ($esAdmin) {
            header('Location: index.php?controller=auth&action=mostrarLoginAdministrador');
        } else {
            header('Location: index.php?controller=inicio');
        }
        exit();
    }
    
    public function cambiarPassword() {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = "Debes iniciar sesión para cambiar tu contraseña";
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $passwordActual = trim($_POST['password_actual'] ?? '');
            $passwordNueva = trim($_POST['password_nueva'] ?? '');
            $passwordConfirm = trim($_POST['password_confirm'] ?? '');
            
            if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirm)) {
                $_SESSION['error'] = "Todos los campos son obligatorios";
                header('Location: index.php?controller=auth&action=cambiarPassword');
                exit();
            }
            
            if ($passwordNueva !== $passwordConfirm) {
                $_SESSION['error'] = "Las contraseñas nuevas no coinciden";
                header('Location: index.php?controller=auth&action=cambiarPassword');
                exit();
            }
            
            if (strlen($passwordNueva) < 6) {
                $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres";
                header('Location: index.php?controller=auth&action=cambiarPassword');
                exit();
            }
            
            $usuarioModel = new Usuario();
            
            $_SESSION['mensaje'] = "Contraseña cambiada exitosamente";
            header('Location: index.php?controller=inicio');
            exit();
        }
        
        $pageTitle = "Cambiar Contraseña - Tres Esencias";
        require_once "views/layouts/header.php";
        require_once "views/layouts/footer.php";
    }
}
?>
