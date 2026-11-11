<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 

$esEdicion = !empty($categoria);
$accionForm = $esEdicion ? 'index.php?accion=categorias/actualizar' : 'index.php?accion=categorias/guardar';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= $esEdicion ? 'Editar Categoría' : 'Nueva Categoría' ?></h1>
        <p class="page-subtitle"><?= $esEdicion ? 'Modifica los datos de la categoría' : 'Crea una nueva clasificación para el catálogo' ?></p>
    </div>
    <div>
        <a href="index.php?accion=categorias" class="btn btn-secondary">
            &larr; Volver al Listado
        </a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="<?= $accionForm ?>" method="POST">
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="nombre">Nombre de la Categoría *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" 
                   value="<?= escapar($categoria['nombre'] ?? '') ?>" placeholder="Ej: Consolas, Videojuegos..." required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" 
                      placeholder="Breve detalle de los artículos que incluye"><?= escapar($categoria['descripcion'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="index.php?accion=categorias" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar Categoría' : 'Guardar Categoría' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
