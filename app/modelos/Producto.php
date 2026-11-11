<?php
// ====================================================================
// Modelo: Producto (RF-04, RF-05, RF-09, RF-15)
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Producto {

    // Listar productos con filtros opcionales de búsqueda y categoría
    public static function listar($busqueda = null, $idCategoria = null, $soloActivos = null) {
        $conexion = obtenerConexion();
        $sql = "
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE 1=1
        ";
        $parametros = [];

        if (!empty($busqueda)) {
            $sql .= " AND (p.nombre LIKE :busqueda OR p.plataforma LIKE :busqueda)";
            $parametros[':busqueda'] = '%' . trim($busqueda) . '%';
        }

        if (!empty($idCategoria)) {
            $sql .= " AND p.id_categoria = :idCat";
            $parametros[':idCat'] = (int)$idCategoria;
        }

        if ($soloActivos !== null) {
            $sql .= " AND p.activo = :activo";
            $parametros[':activo'] = $soloActivos ? 1 : 0;
        }

        $sql .= " ORDER BY p.id_producto DESC";

        $consulta = $conexion->prepare($sql);
        $consulta->execute($parametros);
        return $consulta->fetchAll();
    }

    // Buscar producto por ID
    public static function buscarPorId($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = :id
            LIMIT 1
        ");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Crear producto
    public static function crear($datos) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            INSERT INTO producto (id_categoria, nombre, plataforma, precio, stock, stock_minimo, activo)
            VALUES (:id_categoria, :nombre, :plataforma, :precio, :stock, :stock_minimo, :activo)
        ");
        return $consulta->execute([
            ':id_categoria' => (int)$datos['id_categoria'],
            ':nombre'       => trim($datos['nombre']),
            ':plataforma'   => !empty($datos['plataforma']) ? trim($datos['plataforma']) : null,
            ':precio'       => (float)$datos['precio'],
            ':stock'        => (int)$datos['stock'],
            ':stock_minimo' => (int)$datos['stock_minimo'],
            ':activo'       => isset($datos['activo']) ? (int)$datos['activo'] : 1
        ]);
    }

    // Actualizar producto
    public static function actualizar($id, $datos) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            UPDATE producto
            SET id_categoria = :id_categoria,
                nombre       = :nombre,
                plataforma   = :plataforma,
                precio       = :precio,
                stock        = :stock,
                stock_minimo = :stock_minimo,
                activo       = :activo
            WHERE id_producto = :id
        ");
        return $consulta->execute([
            ':id'           => $id,
            ':id_categoria' => (int)$datos['id_categoria'],
            ':nombre'       => trim($datos['nombre']),
            ':plataforma'   => !empty($datos['plataforma']) ? trim($datos['plataforma']) : null,
            ':precio'       => (float)$datos['precio'],
            ':stock'        => (int)$datos['stock'],
            ':stock_minimo' => (int)$datos['stock_minimo'],
            ':activo'       => isset($datos['activo']) ? (int)$datos['activo'] : 1
        ]);
    }

    // Eliminar producto o desactivarlo si tiene ventas
    public static function eliminar($id) {
        $conexion = obtenerConexion();
        // Verificar si tiene registros en detalle de pedido
        $verificar = $conexion->prepare("SELECT COUNT(*) FROM detalle_pedido WHERE id_producto = :id");
        $verificar->execute([':id' => $id]);
        
        if (((int)$verificar->fetchColumn()) > 0) {
            // Si tiene ventas se desactiva
            $consulta = $conexion->prepare("UPDATE producto SET activo = 0 WHERE id_producto = :id");
            return $consulta->execute([':id' => $id]);
        }

        $consulta = $conexion->prepare("DELETE FROM producto WHERE id_producto = :id");
        return $consulta->execute([':id' => $id]);
    }

    // Métricas para el resumen de inventario
    public static function obtenerMetricas() {
        $conexion = obtenerConexion();
        
        $totalActivos = $conexion->query("SELECT COUNT(*) FROM producto WHERE activo = 1")->fetchColumn();
        $totalUnidades = $conexion->query("SELECT COALESCE(SUM(stock), 0) FROM producto WHERE activo = 1")->fetchColumn();
        $stockBajo = $conexion->query("SELECT COUNT(*) FROM producto WHERE activo = 1 AND stock > 0 AND stock <= stock_minimo")->fetchColumn();
        $agotados = $conexion->query("SELECT COUNT(*) FROM producto WHERE activo = 1 AND stock = 0")->fetchColumn();

        return [
            'total_activos'  => (int)$totalActivos,
            'total_unidades' => (int)$totalUnidades,
            'stock_bajo'     => (int)$stockBajo,
            'agotados'       => (int)$agotados
        ];
    }
}
