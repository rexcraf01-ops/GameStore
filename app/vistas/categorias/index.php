<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Categorías</h1>
        <p class="page-subtitle">Organiza los videojuegos, consolas y accesorios de la tienda</p>
    </div>
    <?php if (esAdministrador()): ?>
    <div>
        <a href="index.php?accion=categorias/crear" class="btn btn-primary">
            + Nueva Categoría
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Total Productos</th>
                    <?php if (esAdministrador()): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categorias)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--color-text-muted);">No hay categorías registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td>#<?= $cat['id_categoria'] ?></td>
                            <td><strong><?= escapar($cat['nombre']) ?></strong></td>
                            <td><?= escapar($cat['descripcion'] ?? 'Sin descripción') ?></td>
                            <td>
                                <span class="badge badge-purple"><?= $cat['total_productos'] ?> productos</span>
                            </td>
                            <?php if (esAdministrador()): ?>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="index.php?accion=categorias/editar&id=<?= $cat['id_categoria'] ?>" class="btn btn-secondary btn-sm">
                                        Editar
                                    </a>
                                    <form action="index.php?accion=categorias/eliminar" method="POST" class="form-eliminar">
                                        <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
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
