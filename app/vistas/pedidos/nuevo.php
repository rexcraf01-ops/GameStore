<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Nuevo Pedido</h1>
        <p class="page-subtitle">Selecciona el cliente y los videojuegos o accesorios para registrar la venta</p>
    </div>
    <div>
        <a href="index.php?accion=pedidos" class="btn btn-secondary">
            &larr; Volver a Pedidos
        </a>
    </div>
</div>

<form action="index.php?accion=pedidos/crear" method="POST" id="form-pedido">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Columna Izquierda: Selección de Productos -->
        <div class="card">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Catálogo de Productos para la Venta</h2>
            
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Plataforma</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th style="width: 120px;">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-text-muted);">No hay productos activos disponibles.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?= escapar($p['nombre']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-purple"><?= escapar($p['plataforma'] ?? 'General') ?></span>
                                    </td>
                                    <td>
                                        <span class="precio-item" data-precio="<?= $p['precio'] ?>">
                                            <?= formatearDinero($p['precio']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($p['stock'] > 0): ?>
                                            <span class="badge badge-success"><?= $p['stock'] ?> disp.</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Agotado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['stock'] > 0): ?>
                                            <input type="number" 
                                                   name="productos[<?= $p['id_producto'] ?>]" 
                                                   class="form-control input-cantidad" 
                                                   min="0" 
                                                   max="<?= $p['stock'] ?>" 
                                                   value="0"
                                                   data-id="<?= $p['id_producto'] ?>"
                                                   data-precio="<?= $p['precio'] ?>"
                                                   style="padding: 6px 10px;">
                                        <?php else: ?>
                                            <span style="font-size: 12px; color: var(--color-text-muted);">Sin stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Columna Derecha: Selección de Cliente y Resumen -->
        <div>
            <div class="card" style="position: sticky; top: 20px;">
                <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Cliente y Resumen</h2>

                <div class="form-group">
                    <label class="form-label" for="id_cliente">Seleccionar Cliente *</label>
                    <select id="id_cliente" name="id_cliente" class="form-control" required>
                        <option value="">-- Seleccione un cliente --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>">
                                <?= escapar($c['nombre']) ?> <?= !empty($c['telefono']) ? '(' . escapar($c['telefono']) . ')' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div style="margin-top: 6px; font-size: 12px;">
                        <a href="index.php?accion=clientes/crear" target="_blank">+ Registrar nuevo cliente</a>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--color-border); margin: 20px 0;">

                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: var(--color-text-muted);">Unidades a comprar:</span>
                        <strong id="resumen-unidades">0</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-size: 16px; font-weight: 600;">Total a Cobrar:</span>
                        <span style="font-size: 26px; font-weight: 800; color: var(--color-purple);" id="resumen-total">$ 0</span>
                    </div>
                    <p style="font-size: 11px; color: var(--color-text-muted); margin-top: 4px;">* El total definitivo se calcula y verifica en el servidor (RN-03).</p>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                    Confirmar y Registrar Pedido
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Cálculo ágil en el navegador mientras el cajero cambia cantidades
const inputsCantidad = document.querySelectorAll('.input-cantidad');
const spanTotal = document.getElementById('resumen-total');
const spanUnidades = document.getElementById('resumen-unidades');

function actualizarTotal() {
    let granTotal = 0;
    let unidadesTotales = 0;

    inputsCantidad.forEach(input => {
        const cantidad = parseInt(input.value) || 0;
        const precio = parseFloat(input.dataset.precio) || 0;
        if (cantidad > 0) {
            unidadesTotales += cantidad;
            granTotal += (cantidad * precio);
        }
    });

    spanUnidades.innerText = unidadesTotales;
    spanTotal.innerText = '$ ' + granTotal.toLocaleString('es-CO');
}

inputsCantidad.forEach(input => {
    input.addEventListener('input', actualizarTotal);
    input.addEventListener('change', actualizarTotal);
});
</script>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
