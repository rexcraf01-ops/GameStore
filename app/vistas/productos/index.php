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
                                    <button type="button"
                                            class="btn btn-secondary btn-sm btn-editar-modal"
                                            data-id="<?= $p['id_producto'] ?>"
                                            data-nombre="<?= escapar($p['nombre']) ?>"
                                            data-categoria="<?= $p['id_categoria'] ?>"
                                            data-plataforma="<?= escapar($p['plataforma'] ?? '') ?>"
                                            data-precio="<?= $p['precio'] ?>"
                                            data-stock="<?= $p['stock'] ?>"
                                            data-stock-minimo="<?= $p['stock_minimo'] ?>"
                                            data-activo="<?= $p['activo'] ?>">
                                        Editar
                                    </button>
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

<!-- ==================================================================== -->
<!-- Ventana Emergente (Modal) para Editar Producto                        -->
<!-- ==================================================================== -->
<div id="modal-editar-producto" class="modal-overlay">
    <div class="modal-dialog" style="max-width: 650px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 14px; margin-bottom: 18px;">
            <h2 class="modal-title" style="font-size: 20px; font-weight: 700;">Editar Información del Producto</h2>
            <button type="button" class="btn-cerrar-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted); line-height: 1;" title="Cerrar">&times;</button>
        </div>

        <form action="index.php?accion=productos/actualizar" method="POST">
            <input type="hidden" name="id_producto" id="modal-id-producto">

            <div class="form-group">
                <label class="form-label" for="modal-nombre">Nombre del Producto / Videojuego *</label>
                <input type="text" id="modal-nombre" name="nombre" class="form-control" required autofocus>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="modal-categoria">Categoría *</label>
                    <select id="modal-categoria" name="id_categoria" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= escapar($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="modal-plataforma">Plataforma / Tipo</label>
                    <input type="text" id="modal-plataforma" name="plataforma" class="form-control" placeholder="Ej: PS5, Switch, Xbox, PC...">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="modal-precio">Precio ($) *</label>
                    <input type="number" step="0.01" min="0" id="modal-precio" name="precio" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="modal-stock">Stock Actual *</label>
                    <input type="number" min="0" id="modal-stock" name="stock" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="modal-stock-minimo">Stock Mínimo *</label>
                    <input type="number" min="0" id="modal-stock-minimo" name="stock_minimo" class="form-control" required>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">
                    <input type="checkbox" name="activo" id="modal-activo" value="1">
                    <span>Producto activo (disponible para venta y visible en catálogo)</span>
                </label>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <button type="button" class="btn btn-secondary btn-cerrar-modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
// Manejo de la ventana emergente (Modal) en JavaScript sencillo
const modalEditar = document.getElementById('modal-editar-producto');
const botonesEditar = document.querySelectorAll('.btn-editar-modal');
const botonesCerrar = document.querySelectorAll('.btn-cerrar-modal');

// Al hacer clic en el botón Editar de una fila
botonesEditar.forEach(boton => {
    boton.addEventListener('click', function() {
        document.getElementById('modal-id-producto').value = this.dataset.id;
        document.getElementById('modal-nombre').value = this.dataset.nombre;
        document.getElementById('modal-categoria').value = this.dataset.categoria;
        document.getElementById('modal-plataforma').value = this.dataset.plataforma;
        document.getElementById('modal-precio').value = this.dataset.precio;
        document.getElementById('modal-stock').value = this.dataset.stock;
        document.getElementById('modal-stock-minimo').value = this.dataset.stockMinimo;
        document.getElementById('modal-activo').checked = (this.dataset.activo === "1");

        // Mostrar la ventana emergente agregando la clase active
        modalEditar.classList.add('active');
    });
});

// Cerrar con los botones Cancelar o X
botonesCerrar.forEach(boton => {
    boton.addEventListener('click', function() {
        modalEditar.classList.remove('active');
    });
});

// Cerrar si hace clic fuera del recuadro blanco
modalEditar.addEventListener('click', function(evento) {
    if (evento.target === modalEditar) {
        modalEditar.classList.remove('active');
    }
});
</script>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
