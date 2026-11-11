<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Clientes</h1>
        <p class="page-subtitle">Directorio de clientes frecuentes y compradores de la tienda</p>
    </div>
    <div>
        <a href="index.php?accion=clientes/crear" class="btn btn-primary">
            + Nuevo Cliente
        </a>
    </div>
</div>

<!-- Barra de Búsqueda -->
<div class="card" style="padding: 16px 24px; margin-bottom: 20px;">
    <form action="index.php" method="GET" style="display: flex; gap: 14px; align-items: center;">
        <input type="hidden" name="accion" value="clientes">
        <input type="text" name="buscar" value="<?= escapar($busqueda ?? '') ?>" class="form-control" placeholder="Buscar por nombre, teléfono o correo...">
        <button type="submit" class="btn btn-secondary">Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="index.php?accion=clientes" class="btn btn-sm" style="color: var(--color-text-muted);">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Total Pedidos</th>
                    <th>Total Comprado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: 32px;">No se encontraron clientes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td>#CLI-<?= $c['id_cliente'] ?></td>
                            <td><strong><?= escapar($c['nombre']) ?></strong></td>
                            <td><?= escapar($c['telefono'] ?? 'Sin registrar') ?></td>
                            <td><?= escapar($c['correo'] ?? 'Sin correo') ?></td>
                            <td>
                                <span class="badge badge-purple"><?= $c['total_pedidos'] ?> pedidos</span>
                            </td>
                            <td><strong><?= formatearDinero($c['total_comprado']) ?></strong></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="index.php?accion=clientes/editar&id=<?= $c['id_cliente'] ?>" class="btn btn-secondary btn-sm">
                                        Editar
                                    </a>
                                    <?php if (esAdministrador()): ?>
                                    <form action="index.php?accion=clientes/eliminar" method="POST" class="form-eliminar">
                                        <input type="hidden" name="id_cliente" value="<?= $c['id_cliente'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
