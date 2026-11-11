<?php
// ====================================================================
// Funciones de ayuda y utilidades en español
// ====================================================================

// Iniciar sesión de forma segura si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redireccionar a una página del sistema
function redireccionar($ruta) {
    $rutaLimpia = ltrim($ruta, '/');
    header("Location: index.php?accion=" . $rutaLimpia);
    exit;
}

// Limpiar y escapar texto para evitar ataques XSS
function escapar($texto) {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Formatear precios en pesos colombianos o moneda local
function formatearDinero($cantidad) {
    return '$ ' . number_format((float)$cantidad, 0, ',', '.');
}

// Verificar si el usuario ha iniciado sesión
function estaAutenticado() {
    return isset($_SESSION['id_usuario']);
}

// Exigir inicio de sesión
function requerirAutenticacion() {
    if (!estaAutenticado()) {
        establecerMensaje('peligro', 'Debe iniciar sesión para continuar.');
        redireccionar('login');
    }
}

// Verificar si es administrador
function esAdministrador() {
    return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1;
}

// Exigir permisos de administrador
function requerirAdministrador() {
    requerirAutenticacion();
    if (!esAdministrador()) {
        establecerMensaje('peligro', 'Acceso denegado: se requieren permisos de Administrador.');
        redireccionar('dashboard');
    }
}

// Guardar mensajes flash para mostrar en la siguiente vista
function establecerMensaje($tipo, $texto) {
    $_SESSION['mensaje'] = [
        'tipo'  => $tipo,  // 'exito', 'peligro', 'advertencia'
        'texto' => $texto
    ];
}

// Obtener y limpiar el mensaje flash
function obtenerMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        return $mensaje;
    }
    return null;
}
