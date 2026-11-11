<?php
// ====================================================================
// Modelo: Categoría (RF-03)
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Categoria {

    // Listar todas las categorías con el conteo de productos
    public static function listar() {
        $conexion = obtenerConexion();
        $consulta = $conexion->query("
            SELECT c.*, COUNT(p.id_producto) AS total_productos
            FROM categoria c
            LEFT JOIN producto p ON c.id_categoria = p.id_categoria
            GROUP BY c.id_categoria, c.nombre, c.descripcion
            ORDER BY c.nombre ASC
        ");
        return $consulta->fetchAll();
    }

    // Buscar una categoría por su ID
    public static function buscarPorId($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("SELECT * FROM categoria WHERE id_categoria = :id LIMIT 1");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Crear una nueva categoría
    public static function crear($nombre, $descripcion) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            INSERT INTO categoria (nombre, descripcion)
            VALUES (:nombre, :descripcion)
        ");
        return $consulta->execute([
            ':nombre'      => trim($nombre),
            ':descripcion' => trim($descripcion)
        ]);
    }

    // Actualizar una categoría existente
    public static function actualizar($id, $nombre, $descripcion) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            UPDATE categoria
            SET nombre = :nombre, descripcion = :descripcion
            WHERE id_categoria = :id
        ");
        return $consulta->execute([
            ':id'          => $id,
            ':nombre'      => trim($nombre),
            ':descripcion' => trim($descripcion)
        ]);
    }

    // Comprobar si la categoría tiene productos asignados
    public static function tieneProductos($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM producto WHERE id_categoria = :id");
        $consulta->execute([':id' => $id]);
        return ((int)$consulta->fetchColumn()) > 0;
    }

    // Eliminar categoría (si no tiene productos vinculados)
    public static function eliminar($id) {
        if (self::tieneProductos($id)) {
            return false;
        }
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("DELETE FROM categoria WHERE id_categoria = :id");
        return $consulta->execute([':id' => $id]);
    }
}
