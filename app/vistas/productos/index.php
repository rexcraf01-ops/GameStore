<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<!-- Encabezado de Productos e Inventario (Figma Pantalla #4) -->
<div class="page-header">
    <div>
        <h1 class="page-title">Productos e Inventario</h1>
        <p class="page-subtitle">Control de precios, stock disponible y reposición de videojuegos</p>
    </div>
    <?php if (esAdministrador()): ?>
    <div>
        <a href="index.php?accion=productos/crear" class="btn btn-primary">
            + Nuevo Producto
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Tarjetas KPI de Inventario -->
<div class="kpi-grid">
    <div class="kpi-card kpi-featured">
        <div class="kpi-label">Productos Activos</div>
        <div class="kpi-value"><?= $metricas['total_activos'] ?></div>
        <div class="kpi-subtext">Disponibles en catálogo</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Unidades en Stock</div>
        <div class="kpi-value"><?= $metricas['total_unidades'] ?></div>
        <div class="kpi-subtext">Total físico disponible</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Stock Bajo</div>
        <div class="kpi-value" style="color: var(--color-warning-text);"><?= $metricas['stock_bajo'] ?></div>
        <div class="kpi-subtext">Cerca del mínimo</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Sin Existencias</div>
        <div class="kpi-value" style="color: var(--color-danger-text);"><?= $metricas['agotados'] ?></div>
        <div class="kpi-subtext">Juegos agotados</div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card" style="padding: 16px 24px; margin-bottom: 20px;">
    <form action="index.php" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
        <input type="hidden" name="accion" value="productos">

        <div style="flex: 1; min-width: 240px;">
            <input type="text" name="buscar" value="<?= escapar($busqueda ?? '') ?>" class="form-control" placeholder="Buscar por nombre o plataforma...">
        </div>

        <div style="width: 220px;">
            <select name="categoria" class="form-control">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= ($idCategoria == $cat['id_categoria']) ? 'selected' : '' ?>>
                        <?= escapar($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary">Filtrar</button>
            <?php if (!empty($busqueda) || !empty($idCategoria)): ?>
                <a href="index.php?accion=productos" class="btn btn-sm" style="color: var(--color-text-muted);">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabla de Productos -->
<div class="card">
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>SKU / ID</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Plataforma</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <?php if (esAdministrador()): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--color-text-muted); padding: 32px;">
                            No se encontraron productos que coincidan con la búsqueda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td>#SKU-<?= str_pad($p['id_producto'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <strong><?= escapar($p['nombre']) ?></strong>
                            </td>
                            <td><?= escapar($p['categoria_nombre']) ?></td>
                            <td>
                                <span class="badge badge-purple"><?= escapar($p['plataforma'] ?? 'General') ?></span>
                            </td>
                            <td><strong><?= formatearDinero($p['precio']) ?></strong></td>
                            <td>
                                <strong><?= $p['stock'] ?></strong> 
                                <span style="font-size: 11px; color: var(--color-text-muted);">(Mín: <?= $p['stock_minimo'] ?>)</span>
                            </td>
                            <td>
                                <?php if (!$p['activo']): ?>
                                    <span class="badge badge-dark">Inactivo</span>
                                <?php elseif ($p['stock'] == 0): ?>
                                    <span class="badge badge-danger">Agotado</span>
                                <?php elseif ($p['stock'] <= $p['stock_minimo']): ?>
                                    <span class="badge badge-warning">Stock bajo</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Disponible</span>
                                <?php endif; ?>
                            </td>
                            <?php if (esAdministrador()): ?>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="index.php?accion=productos/editar&id=<?= $p['id_producto'] ?>" class="btn btn-secondary btn-sm">
                                        Editar
                                    </a>
                                    <form action="index.php?accion=productos/eliminar" method="POST" class="form-eliminar">
                                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
