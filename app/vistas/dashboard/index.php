<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<!-- Encabezado del Panel (Figma Pantalla #2) -->
<div class="page-header">
    <div>
        <h1 class="page-title">Tu tienda, de un vistazo</h1>
        <p class="page-subtitle">Resumen de ventas del día, pedidos pendientes y control de inventario</p>
    </div>
    <div>
        <a href="index.php?accion=pedidos/nuevo" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            + Nuevo Pedido
        </a>
    </div>
</div>

<!-- Tarjetas KPI -->
<div class="kpi-grid">
    <!-- 1. Tarjeta destacada en morado -->
    <div class="kpi-card kpi-featured">
        <div class="kpi-label">Ventas de Hoy (Caja)</div>
        <div class="kpi-value"><?= formatearDinero($ventasHoy) ?></div>
        <div class="kpi-subtext"><?= $transaccionesHoy ?> cobro(s) registrado(s)</div>
    </div>

    <!-- 2. Pedidos pendientes -->
    <div class="kpi-card">
        <div class="kpi-label">Pedidos Pendientes</div>
        <div class="kpi-value"><?= $pedidosPendientes ?></div>
        <div class="kpi-subtext">Por preparar o entregar</div>
    </div>

    <!-- 3. Alertas de stock bajo -->
    <div class="kpi-card">
        <div class="kpi-label">Stock Bajo</div>
        <div class="kpi-value" style="color: var(--color-warning-text);"><?= $metricasStock['stock_bajo'] ?></div>
        <div class="kpi-subtext"><?= $metricasStock['agotados'] ?> juego(s) agotado(s)</div>
    </div>

    <!-- 4. Productos activos -->
    <div class="kpi-card">
        <div class="kpi-label">Productos Activos</div>
        <div class="kpi-value"><?= $metricasStock['total_activos'] ?></div>
        <div class="kpi-subtext"><?= $metricasStock['total_unidades'] ?> unidades totales</div>
    </div>
</div>

<!-- Contenedor a dos columnas -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Columna Izquierda: Pedidos Recientes -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 700;">Pedidos Recientes</h2>
            <a href="index.php?accion=pedidos" class="btn btn-secondary btn-sm">Ver todos &rarr;</a>
        </div>

        <?php if (empty($ultimosPedidos)): ?>
            <p style="color: var(--color-text-muted); text-align: center; padding: 24px;">No hay pedidos registrados aún.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosPedidos as $ped): ?>
                            <tr>
                                <td><strong>PED-<?= $ped['id_pedido'] ?></strong></td>
                                <td><?= escapar($ped['cliente_nombre']) ?></td>
                                <td>
                                    <?php if ($ped['estado'] === 'entregado'): ?>
                                        <span class="badge badge-success">Entregado</span>
                                    <?php elseif ($ped['estado'] === 'alistado'): ?>
                                        <span class="badge badge-purple">En preparación</span>
                                    <?php elseif ($ped['estado'] === 'cancelado'): ?>
                                        <span class="badge badge-danger">Cancelado</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= formatearDinero($ped['total']) ?></strong></td>
                                <td>
                                    <a href="index.php?accion=pedidos/detalle&id=<?= $ped['id_pedido'] ?>" class="btn btn-secondary btn-sm">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Columna Derecha: Top Productos y Alertas -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Top más vendidos -->
        <div class="card">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 14px;">Más Vendidos</h2>
            <?php if (empty($productosMasVendidos)): ?>
                <p style="color: var(--color-text-muted); font-size: 14px;">No hay estadísticas de venta aún.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach ($productosMasVendidos as $top): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;"><?= escapar($top['nombre']) ?></div>
                                <div style="font-size: 12px; color: var(--color-text-muted);"><?= escapar($top['plataforma'] ?? 'General') ?></div>
                            </div>
                            <span class="badge badge-purple"><?= $top['total_vendido'] ?> vendidos</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Alerta de Stock Bajo -->
        <div class="card">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 14px;">Prioridades de Reposición</h2>
            <?php if (empty($productosStockBajo)): ?>
                <p style="color: var(--color-success-text); font-size: 14px;">El stock de todos los productos está en niveles óptimos.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <?php foreach ($productosStockBajo as $bajo): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 14px; font-weight: 500;"><?= escapar($bajo['nombre']) ?></span>
                            <span class="badge badge-danger"><?= $bajo['stock'] ?> en stock</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
