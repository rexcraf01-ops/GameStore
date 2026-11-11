<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Pedidos</h1>
        <p class="page-subtitle">Registro de ventas en caja, seguimiento de entregas y cobros</p>
    </div>
    <div>
        <a href="index.php?accion=pedidos/nuevo" class="btn btn-primary">
            + Registrar Pedido
        </a>
    </div>
</div>

<!-- Filtro por Estado -->
<div class="card" style="padding: 16px 24px; margin-bottom: 20px;">
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 13px; font-weight: 600; color: var(--color-text-muted);">Filtrar por estado:</span>
        <a href="index.php?accion=pedidos&estado=todos" class="btn btn-sm <?= ($estadoFiltro === 'todos' || empty($estadoFiltro)) ? 'btn-primary' : 'btn-secondary' ?>">Todos</a>
        <a href="index.php?accion=pedidos&estado=pendiente" class="btn btn-sm <?= ($estadoFiltro === 'pendiente') ? 'btn-primary' : 'btn-secondary' ?>">Pendientes</a>
        <a href="index.php?accion=pedidos&estado=alistado" class="btn btn-sm <?= ($estadoFiltro === 'alistado') ? 'btn-primary' : 'btn-secondary' ?>">En preparación</a>
        <a href="index.php?accion=pedidos&estado=entregado" class="btn btn-sm <?= ($estadoFiltro === 'entregado') ? 'btn-primary' : 'btn-secondary' ?>">Entregados</a>
        <a href="index.php?accion=pedidos&estado=cancelado" class="btn btn-sm <?= ($estadoFiltro === 'cancelado') ? 'btn-primary' : 'btn-secondary' ?>">Cancelados</a>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nº Pedido</th>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
                    <th>Atendido por</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--color-text-muted); padding: 32px;">
                            No se encontraron pedidos en esta sección.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td><strong>PED-<?= $p['id_pedido'] ?></strong></td>
                            <td style="font-size: 13px; color: var(--color-text-muted);"><?= date('d/m/Y H:i', strtotime($p['fecha_pedido'])) ?></td>
                            <td><?= escapar($p['cliente_nombre']) ?></td>
                            <td style="font-size: 13px;"><?= escapar($p['usuario_nombre']) ?></td>
                            <td><strong><?= formatearDinero($p['total']) ?></strong></td>
                            <td>
                                <?php if ($p['estado'] === 'entregado'): ?>
                                    <span class="badge badge-success">Entregado</span>
                                <?php elseif ($p['estado'] === 'alistado'): ?>
                                    <span class="badge badge-purple">En preparación</span>
                                <?php elseif ($p['estado'] === 'cancelado'): ?>
                                    <span class="badge badge-danger">Cancelado</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($p['metodo_pago'])): ?>
                                    <span class="badge badge-success"><?= ucfirst($p['metodo_pago']) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Por cobrar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?accion=pedidos/detalle&id=<?= $p['id_pedido'] ?>" class="btn btn-secondary btn-sm">
                                    Detalle &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
