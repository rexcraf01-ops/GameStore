<?php
// ====================================================================
// Controlador: Autenticación (RF-01, RF-02)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Usuario.php';

class AuthController {

    // Mostrar el formulario de inicio de sesión
    public function mostrarLogin() {
        if (estaAutenticado()) {
            redireccionar('dashboard');
        }
        $titulo = "Iniciar Sesión | Level Up Games";
        require_once __DIR__ . '/../vistas/auth/login.php';
    }

    // Procesar el formulario de inicio de sesión
    public function iniciarSesion() {
        $correo     = trim($_POST['correo'] ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');

        if (empty($correo) || empty($contrasena)) {
            establecerMensaje('peligro', 'Por favor ingrese el correo y la contraseña.');
            redireccionar('login');
        }

        $usuario = Usuario::buscarPorCorreo($correo);

        if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
            establecerMensaje('peligro', 'Credenciales incorrectas. Verifique correo o clave.');
            redireccionar('login');
        }

        if (!$usuario['activo']) {
            establecerMensaje('peligro', 'Su usuario está inactivo. Contacte al administrador.');
            redireccionar('login');
        }

        // Guardar variables de sesión
        $_SESSION['id_usuario']     = $usuario['id_usuario'];
        $_SESSION['nombre_usuario'] = $usuario['nombre'];
        $_SESSION['correo_usuario'] = $usuario['correo'];
        $_SESSION['id_rol']         = $usuario['id_rol'];
        $_SESSION['rol_nombre']     = $usuario['rol_nombre'];

        establecerMensaje('exito', "¡Bienvenido al sistema, {$usuario['nombre']}!");
        redireccionar('dashboard');
    }

    // Cerrar sesión
    public function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        session_start();
        establecerMensaje('exito', 'Ha cerrado sesión correctamente.');
        redireccionar('login');
    }
}
