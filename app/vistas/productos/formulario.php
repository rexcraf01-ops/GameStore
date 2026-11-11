<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 

$esEdicion = !empty($producto);
$accionForm = $esEdicion ? 'index.php?accion=productos/actualizar' : 'index.php?accion=productos/guardar';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= $esEdicion ? 'Editar Producto' : 'Nuevo Producto' ?></h1>
        <p class="page-subtitle"><?= $esEdicion ? 'Actualiza los datos del producto en el catálogo' : 'Ingresa la información del nuevo videojuego o accesorio' ?></p>
    </div>
    <div>
        <a href="index.php?accion=productos" class="btn btn-secondary">
            &larr; Volver al Listado
        </a>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <form action="<?= $accionForm ?>" method="POST">
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="nombre">Nombre del Producto / Videojuego *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" 
                   value="<?= escapar($producto['nombre'] ?? '') ?>" placeholder="Ej: PlayStation 5 Slim, EA Sports FC 26..." required autofocus>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="id_categoria">Categoría *</label>
                <select id="id_categoria" name="id_categoria" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= (isset($producto['id_categoria']) && $producto['id_categoria'] == $cat['id_categoria']) ? 'selected' : '' ?>>
                            <?= escapar($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="plataforma">Plataforma / Tipo</label>
                <input type="text" id="plataforma" name="plataforma" class="form-control" 
                       value="<?= escapar($producto['plataforma'] ?? '') ?>" placeholder="Ej: PS5, Switch, Xbox, PC...">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="precio">Precio de Venta ($) *</label>
                <input type="number" step="0.01" min="0" id="precio" name="precio" class="form-control" 
                       value="<?= escapar($producto['precio'] ?? '') ?>" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="stock">Stock Actual *</label>
                <input type="number" min="0" id="stock" name="stock" class="form-control" 
                       value="<?= escapar($producto['stock'] ?? '0') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="stock_minimo">Stock Mínimo Alerta *</label>
                <input type="number" min="0" id="stock_minimo" name="stock_minimo" class="form-control" 
                       value="<?= escapar($producto['stock_minimo'] ?? '2') ?>" required>
            </div>
        </div>

        <div class="form-group" style="margin-top: 10px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">
                <input type="checkbox" name="activo" value="1" <?= (!isset($producto['activo']) || $producto['activo'] == 1) ? 'checked' : '' ?>>
                <span>Producto activo (visible en el catálogo público y disponible para pedidos)</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="index.php?accion=productos" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar Producto' : 'Guardar Producto' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
