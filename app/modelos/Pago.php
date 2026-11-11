<?php
// ====================================================================
// Modelo: Pago (RF-11, RF-14)
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Pago {

    // Listar todos los pagos con información del cliente
    public static function listar() {
        $conexion = obtenerConexion();
        $consulta = $conexion->query("
            SELECT pg.*, p.id_cliente, c.nombre AS cliente_nombre, p.estado AS estado_pedido
            FROM pago pg
            INNER JOIN pedido p ON pg.id_pedido = p.id_pedido
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            ORDER BY pg.fecha_pago DESC
        ");
        return $consulta->fetchAll();
    }

    // Buscar pago de un pedido específico
    public static function buscarPorPedido($idPedido) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("SELECT * FROM pago WHERE id_pedido = :id LIMIT 1");
        $consulta->execute([':id' => $idPedido]);
        return $consulta->fetch();
    }

    // Totales agrupados por método de pago para reportes
    public static function obtenerPorMetodos($desde = null, $hasta = null) {
        $conexion = obtenerConexion();
        $sql = "
            SELECT metodo_pago, COUNT(*) AS total_transacciones, COALESCE(SUM(monto), 0) AS monto_total
            FROM pago
            WHERE 1=1
        ";
        $parametros = [];

        if (!empty($desde)) {
            $sql .= " AND fecha_pago >= :desde";
            $parametros[':desde'] = $desde . ' 00:00:00';
        }

        if (!empty($hasta)) {
            $sql .= " AND fecha_pago <= :hasta";
            $parametros[':hasta'] = $hasta . ' 23:59:59';
        }

        $sql .= " GROUP BY metodo_pago";

        $consulta = $conexion->prepare($sql);
        $consulta->execute($parametros);
        return $consulta->fetchAll();
    }
}
