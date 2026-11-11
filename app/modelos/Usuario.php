<?php
// ====================================================================
// Modelo: Usuario
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Usuario {

    // Buscar usuario por correo electrónico para el inicio de sesión
    public static function buscarPorCorreo($correo) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            SELECT u.*, r.nombre AS rol_nombre 
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol
            WHERE u.correo = :correo
            LIMIT 1
        ");
        $consulta->execute([':correo' => trim($correo)]);
        return $consulta->fetch();
    }

    // Buscar usuario por su ID
    public static function buscarPorId($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            SELECT u.*, r.nombre AS rol_nombre 
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = :id
            LIMIT 1
        ");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Listar todos los usuarios
    public static function listar() {
        $conexion = obtenerConexion();
        $consulta = $conexion->query("
            SELECT u.id_usuario, u.nombre, u.correo, u.activo, u.fecha_creacion, r.nombre AS rol_nombre
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol
            ORDER BY u.nombre ASC
        ");
        return $consulta->fetchAll();
    }
}
