<?php
// ====================================================================
// Controlador: Reportes y Estadísticas (RF-14)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../modelos/Pago.php';

class ReporteController {

    // Pantalla de reportes
    public function index() {
        requerirAdministrador();

        $conexion = obtenerConexion();

        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        // 1. Total recaudado en el rango
        $consultaTotal = $conexion->prepare("
            SELECT COALESCE(SUM(monto), 0) AS total_ventas, COUNT(*) AS total_pedidos
            FROM pago
            WHERE fecha_pago >= :desde AND fecha_pago <= :hasta
        ");
        $consultaTotal->execute([
            ':desde' => $desde . ' 00:00:00',
            ':hasta' => $hasta . ' 23:59:59'
        ]);
        $resumenVentas = $consultaTotal->fetch();

        // 2. Ventas agrupadas por método de pago
        $metodosPago = Pago::obtenerPorMetodos($desde, $hasta);

        // 3. Clientes con mayor número de compras en el período
        $consultaClientes = $conexion->prepare("
            SELECT c.nombre, c.correo, COUNT(p.id_pedido) AS pedidos_realizados, SUM(p.total) AS total_gastado
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            WHERE p.estado != 'cancelado' 
              AND p.fecha_pedido >= :desde AND p.fecha_pedido <= :hasta
            GROUP BY c.id_cliente, c.nombre, c.correo
            ORDER BY total_gastado DESC
            LIMIT 5
        ");
        $consultaClientes->execute([
            ':desde' => $desde . ' 00:00:00',
            ':hasta' => $hasta . ' 23:59:59'
        ]);
        $mejoresClientes = $consultaClientes->fetchAll();

        // 4. Productos más vendidos en el período
        $consultaTopProd = $conexion->prepare("
            SELECT pr.nombre, pr.plataforma, SUM(dp.cantidad) AS unidades_vendidas, SUM(dp.subtotal) AS ingresos_generados
            FROM detalle_pedido dp
            INNER JOIN producto pr ON dp.id_producto = pr.id_producto
            INNER JOIN pedido p ON dp.id_pedido = p.id_pedido
            WHERE p.estado != 'cancelado'
              AND p.fecha_pedido >= :desde AND p.fecha_pedido <= :hasta
            GROUP BY pr.id_producto, pr.nombre, pr.plataforma
            ORDER BY unidades_vendidas DESC
            LIMIT 5
        ");
        $consultaTopProd->execute([
            ':desde' => $desde . ' 00:00:00',
            ':hasta' => $hasta . ' 23:59:59'
        ]);
        $productosTop = $consultaTopProd->fetchAll();

        $titulo = "Reportes y Ventas | Level Up Games";
        require_once __DIR__ . '/../vistas/reportes/index.php';
    }

    // Exportar datos a archivo CSV (RF-14)
    public function exportarCSV() {
        requerirAdministrador();

        $conexion = obtenerConexion();
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $consulta = $conexion->prepare("
            SELECT p.id_pedido, p.fecha_pedido, c.nombre AS cliente, p.estado, p.total,
                   COALESCE(pg.metodo_pago, 'Sin pago') AS metodo_pago, pg.fecha_pago
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            LEFT JOIN pago pg ON p.id_pedido = pg.id_pedido
            WHERE p.fecha_pedido >= :desde AND p.fecha_pedido <= :hasta
            ORDER BY p.id_pedido DESC
        ");
        $consulta->execute([
            ':desde' => $desde . ' 00:00:00',
            ':hasta' => $hasta . ' 23:59:59'
        ]);
        $filas = $consulta->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_ventas_levelup_' . date('Ymd_His') . '.csv');

        $salida = fopen('php://output', 'w');
        // Encabezados en español
        fputcsv($salida, ['ID Pedido', 'Fecha Pedido', 'Cliente', 'Estado', 'Total ($)', 'Metodo Pago', 'Fecha Pago'], ';');

        foreach ($filas as $fila) {
            fputcsv($salida, [
                $fila['id_pedido'],
                $fila['fecha_pedido'],
                $fila['cliente'],
                $fila['estado'],
                $fila['total'],
                $fila['metodo_pago'],
                $fila['fecha_pago'] ?? ''
            ], ';');
        }

        fclose($salida);
        exit;
    }
}
