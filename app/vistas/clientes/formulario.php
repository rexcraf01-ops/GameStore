<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 

$esEdicion = !empty($cliente);
$accionForm = $esEdicion ? 'index.php?accion=clientes/actualizar' : 'index.php?accion=clientes/guardar';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= $esEdicion ? 'Editar Cliente' : 'Nuevo Cliente' ?></h1>
        <p class="page-subtitle"><?= $esEdicion ? 'Modifica los datos de contacto del cliente' : 'Registra un nuevo cliente para asociarlo a los pedidos' ?></p>
    </div>
    <div>
        <a href="index.php?accion=clientes" class="btn btn-secondary">
            &larr; Volver al Listado
        </a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="<?= $accionForm ?>" method="POST">
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" 
                   value="<?= escapar($cliente['nombre'] ?? '') ?>" placeholder="Ej: Laura Pérez" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="telefono">Teléfono / Celular</label>
            <input type="text" id="telefono" name="telefono" class="form-control" 
                   value="<?= escapar($cliente['telefono'] ?? '') ?>" placeholder="Ej: 3001234567">
        </div>

        <div class="form-group">
            <label class="form-label" for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" 
                   value="<?= escapar($cliente['correo'] ?? '') ?>" placeholder="cliente@correo.com">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="index.php?accion=clientes" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar Cliente' : 'Guardar Cliente' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
