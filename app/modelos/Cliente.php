<?php
// ====================================================================
// Modelo: Cliente (RF-06)
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Cliente {

    // Listar clientes con búsqueda opcional
    public static function listar($busqueda = null) {
        $conexion = obtenerConexion();
        $sql = "
            SELECT c.*, COUNT(p.id_pedido) AS total_pedidos, COALESCE(SUM(p.total), 0) AS total_comprado
            FROM cliente c
            LEFT JOIN pedido p ON c.id_cliente = p.id_cliente AND p.estado != 'cancelado'
            WHERE 1=1
        ";
        $parametros = [];

        if (!empty($busqueda)) {
            $sql .= " AND (c.nombre LIKE :busqueda OR c.telefono LIKE :busqueda OR c.correo LIKE :busqueda)";
            $parametros[':busqueda'] = '%' . trim($busqueda) . '%';
        }

        $sql .= " GROUP BY c.id_cliente, c.nombre, c.telefono, c.correo, c.fecha_registro ORDER BY c.id_cliente DESC";

        $consulta = $conexion->prepare($sql);
        $consulta->execute($parametros);
        return $consulta->fetchAll();
    }

    // Buscar cliente por ID
    public static function buscarPorId($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("SELECT * FROM cliente WHERE id_cliente = :id LIMIT 1");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Registrar nuevo cliente
    public static function crear($datos) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            INSERT INTO cliente (nombre, telefono, correo)
            VALUES (:nombre, :telefono, :correo)
        ");
        return $consulta->execute([
            ':nombre'   => trim($datos['nombre']),
            ':telefono' => !empty($datos['telefono']) ? trim($datos['telefono']) : null,
            ':correo'   => !empty($datos['correo']) ? trim($datos['correo']) : null
        ]);
    }

    // Actualizar datos del cliente
    public static function actualizar($id, $datos) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            UPDATE cliente
            SET nombre = :nombre, telefono = :telefono, correo = :correo
            WHERE id_cliente = :id
        ");
        return $consulta->execute([
            ':id'       => $id,
            ':nombre'   => trim($datos['nombre']),
            ':telefono' => !empty($datos['telefono']) ? trim($datos['telefono']) : null,
            ':correo'   => !empty($datos['correo']) ? trim($datos['correo']) : null
        ]);
    }

    // Eliminar cliente si no tiene pedidos registrados
    public static function eliminar($id) {
        $conexion = obtenerConexion();
        $verificar = $conexion->prepare("SELECT COUNT(*) FROM pedido WHERE id_cliente = :id");
        $verificar->execute([':id' => $id]);
        
        if (((int)$verificar->fetchColumn()) > 0) {
            return false;
        }

        $consulta = $conexion->prepare("DELETE FROM cliente WHERE id_cliente = :id");
        return $consulta->execute([':id' => $id]);
    }
}
