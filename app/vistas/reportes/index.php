<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 

// Fechas para los botones rápidos
$hoy = date('Y-m-d');
$hace7Dias = date('Y-m-d', strtotime('-7 days'));
$hace30Dias = date('Y-m-d', strtotime('-30 days'));
?>

<!-- Encabezado de Reportes (Figma Pantalla #8 con selector Desde/Hasta) -->
<div class="page-header">
    <div>
        <h1 class="page-title">Reportes y Estadísticas</h1>
        <p class="page-subtitle">Ingresos, medios de pago y clientes frecuentes por período</p>
    </div>
    <div>
        <a href="index.php?accion=reportes/csv&desde=<?= $desde ?>&hasta=<?= $hasta ?>" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Exportar CSV
        </a>
    </div>
</div>

<!-- Barra de Filtro de Fechas -->
<div class="card" style="padding: 18px 24px; margin-bottom: 24px;">
    <form action="index.php" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
        <input type="hidden" name="accion" value="reportes">

        <div>
            <label class="form-label" for="desde">Desde:</label>
            <input type="date" id="desde" name="desde" value="<?= escapar($desde) ?>" class="form-control" required>
        </div>

        <div>
            <label class="form-label" for="hasta">Hasta:</label>
            <input type="date" id="hasta" name="hasta" value="<?= escapar($hasta) ?>" class="form-control" required>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary">Filtrar</button>
        </div>

        <!-- Accesos rápidos requeridos en Sesión 4 -->
        <div style="display: flex; gap: 8px; align-items: center; margin-left: auto;">
            <span style="font-size: 13px; color: var(--color-text-muted);">Accesos rápidos:</span>
            <a href="index.php?accion=reportes&desde=<?= $hoy ?>&hasta=<?= $hoy ?>" class="btn btn-secondary btn-sm">Hoy</a>
            <a href="index.php?accion=reportes&desde=<?= $hace7Dias ?>&hasta=<?= $hoy ?>" class="btn btn-secondary btn-sm">Últimos 7 días</a>
            <a href="index.php?accion=reportes&desde=<?= $hace30Dias ?>&hasta=<?= $hoy ?>" class="btn btn-secondary btn-sm">Últimos 30 días</a>
        </div>
    </form>
</div>

<!-- Tarjetas KPI del Reporte -->
<div class="kpi-grid">
    <div class="kpi-card kpi-featured">
        <div class="kpi-label">Ingresos del Período</div>
        <div class="kpi-value"><?= formatearDinero($resumenVentas['total_ventas']) ?></div>
        <div class="kpi-subtext">Total cobrado con éxito</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Ventas Cobradas</div>
        <div class="kpi-value"><?= $resumenVentas['total_pedidos'] ?></div>
        <div class="kpi-subtext">Transacciones registradas</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Rango Consultado</div>
        <div class="kpi-value" style="font-size: 20px;"><?= date('d/m/Y', strtotime($desde)) ?></div>
        <div class="kpi-subtext">hasta <?= date('d/m/Y', strtotime($hasta)) ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
    <!-- Métodos de Pago -->
    <div class="card">
        <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Ventas por Método de Pago</h2>
        <?php if (empty($metodosPago)): ?>
            <p style="color: var(--color-text-muted); font-size: 14px;">No hay registros de pago en este rango de fechas.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Método</th>
                            <th>Transacciones</th>
                            <th>Total Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($metodosPago as $mp): ?>
                            <tr>
                                <td><strong><?= ucfirst($mp['metodo_pago']) ?></strong></td>
                                <td><span class="badge badge-purple"><?= $mp['total_transacciones'] ?> cobros</span></td>
                                <td><strong><?= formatearDinero($mp['monto_total']) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Clientes Frecuentes -->
    <div class="card">
        <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Clientes con Mayor Compra</h2>
        <?php if (empty($mejoresClientes)): ?>
            <p style="color: var(--color-text-muted); font-size: 14px;">No hay compras de clientes en este período.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Pedidos</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mejoresClientes as $mc): ?>
                            <tr>
                                <td>
                                    <strong><?= escapar($mc['nombre']) ?></strong>
                                    <div style="font-size: 12px; color: var(--color-text-muted);"><?= escapar($mc['correo'] ?? '') ?></div>
                                </td>
                                <td><span class="badge badge-purple"><?= $mc['pedidos_realizados'] ?> compras</span></td>
                                <td><strong><?= formatearDinero($mc['total_gastado']) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Videojuegos más vendidos en el período -->
<div class="card">
    <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Videojuegos y Productos Más Vendidos</h2>
    <?php if (empty($productosTop)): ?>
        <p style="color: var(--color-text-muted); font-size: 14px;">No se registraron ventas en las fechas seleccionadas.</p>
    <?php else: ?>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Plataforma</th>
                        <th>Unidades Vendidas</th>
                        <th>Total Recaudado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosTop as $pt): ?>
                        <tr>
                            <td><strong><?= escapar($pt['nombre']) ?></strong></td>
                            <td><span class="badge badge-purple"><?= escapar($pt['plataforma'] ?? 'General') ?></span></td>
                            <td><strong><?= $pt['unidades_vendidas'] ?></strong></td>
                            <td><strong><?= formatearDinero($pt['ingresos_generados']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
