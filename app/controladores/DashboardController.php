<?php
// ====================================================================
// Controlador: Panel de Control / Dashboard (RF-13)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../modelos/Producto.php';
require_once __DIR__ . '/../modelos/Pedido.php';

class DashboardController {

    public function index() {
        requerirAutenticacion();

        $conexion = obtenerConexion();

        // 1. Total ventas del día en pagos registrados
        $consultaHoy = $conexion->query("
            SELECT COALESCE(SUM(monto), 0) AS total_hoy, COUNT(*) AS transacciones_hoy
            FROM pago
            WHERE DATE(fecha_pago) = CURRENT_DATE
        ");
        $ventasHoy = $consultaHoy->fetch();

        // 2. Cantidad de pedidos pendientes
        $pedidosPendientes = (int)$conexion->query("
            SELECT COUNT(*) FROM pedido WHERE estado = 'pendiente'
        ")->fetchColumn();

        // 3. Métricas de productos e inventario
        $metricasStock = Producto::obtenerMetricas();

        // 4. Últimos pedidos registrados
        $ultimosPedidos = $conexion->query("
            SELECT p.*, c.nombre AS cliente_nombre, pg.metodo_pago
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            LEFT JOIN pago pg ON p.id_pedido = pg.id_pedido
            ORDER BY p.id_pedido DESC
            LIMIT 5
        ")->fetchAll();

        // 5. Productos más vendidos
        $productosMasVendidos = $conexion->query("
            SELECT pr.nombre, pr.plataforma, SUM(dp.cantidad) AS total_vendido, SUM(dp.subtotal) AS total_recaudado
            FROM detalle_pedido dp
            INNER JOIN producto pr ON dp.id_producto = pr.id_producto
            INNER JOIN pedido p ON dp.id_pedido = p.id_pedido
            WHERE p.estado != 'cancelado'
            GROUP BY pr.id_producto, pr.nombre, pr.plataforma
            ORDER BY total_vendido DESC
            LIMIT 5
        ")->fetchAll();

        // 6. Productos con pocas existencias (stock bajo)
        $productosStockBajo = $conexion->query("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM producto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE p.activo = 1 AND p.stock <= p.stock_minimo
            ORDER BY p.stock ASC
            LIMIT 5
        ")->fetchAll();

        $titulo = "Panel Principal | Level Up Games";
        require_once __DIR__ . '/../vistas/dashboard/index.php';
    }
}
