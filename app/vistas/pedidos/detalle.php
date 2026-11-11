<?php 
require_once __DIR__ . '/../plantilla/cabecera.php'; 
require_once __DIR__ . '/../plantilla/menu_lateral.php'; 
?>

<!-- Encabezado de Detalle de Pedido (Figma Pantalla #6) -->
<div class="page-header">
    <div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <h1 class="page-title">Pedido PED-<?= $pedido['id_pedido'] ?></h1>
            <?php if ($pedido['estado'] === 'entregado'): ?>
                <span class="badge badge-success">Entregado</span>
            <?php elseif ($pedido['estado'] === 'alistado'): ?>
                <span class="badge badge-purple">En preparación</span>
            <?php elseif ($pedido['estado'] === 'cancelado'): ?>
                <span class="badge badge-danger">Cancelado</span>
            <?php else: ?>
                <span class="badge badge-warning">Pendiente de confirmación</span>
            <?php endif; ?>
        </div>
        <p class="page-subtitle">Registrado el <?= date('d/m/Y \a \l\a\s H:i', strtotime($pedido['fecha_pedido'])) ?> por <?= escapar($pedido['usuario_nombre']) ?></p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.print()" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Imprimir Pedido
        </button>
        <a href="index.php?accion=pedidos" class="btn btn-secondary">
            &larr; Volver
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Columna Izquierda: Artículos del Pedido -->
    <div class="card">
        <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Artículos y Videojuegos</h2>

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Plataforma</th>
                        <th>Cant.</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $det): ?>
                        <tr>
                            <td><strong><?= escapar($det['producto_nombre']) ?></strong></td>
                            <td><span class="badge badge-purple"><?= escapar($det['plataforma'] ?? 'General') ?></span></td>
                            <td><?= $det['cantidad'] ?></td>
                            <td><?= formatearDinero($det['precio_unitario']) ?></td>
                            <td><strong><?= formatearDinero($det['subtotal']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <div style="text-align: right;">
                <div style="font-size: 14px; color: var(--color-text-muted);">Total del Pedido:</div>
                <div style="font-size: 28px; font-weight: 800; color: var(--color-purple);"><?= formatearDinero($pedido['total']) ?></div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Cliente, Pago y Acciones -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Tarjeta de Cliente -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">Datos del Cliente</h3>
            <div style="margin-bottom: 8px;">
                <span style="font-size: 12px; color: var(--color-text-muted); display: block;">Nombre:</span>
                <strong><?= escapar($pedido['cliente_nombre']) ?></strong>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="font-size: 12px; color: var(--color-text-muted); display: block;">Teléfono:</span>
                <span><?= escapar($pedido['cliente_telefono'] ?? 'No registrado') ?></span>
            </div>
            <div>
                <span style="font-size: 12px; color: var(--color-text-muted); display: block;">Correo:</span>
                <span><?= escapar($pedido['cliente_correo'] ?? 'No registrado') ?></span>
            </div>
        </div>

        <!-- Tarjeta de Cobro / Pago en Caja -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">Estado del Cobro</h3>

            <?php if (!empty($pedido['metodo_pago'])): ?>
                <div style="background-color: var(--color-success-bg); border-radius: var(--radius-btn); padding: 14px; margin-bottom: 12px;">
                    <div style="color: var(--color-success-text); font-weight: 700; font-size: 15px;">✓ Pago Confirmado</div>
                    <div style="font-size: 13px; color: var(--color-success-text); margin-top: 4px;">
                        Método: <strong><?= ucfirst($pedido['metodo_pago']) ?></strong><br>
                        Monto: <strong><?= formatearDinero($pedido['monto_pago']) ?></strong><br>
                        Fecha: <?= date('d/m/Y H:i', strtotime($pedido['fecha_pago'])) ?>
                    </div>
                </div>
            <?php else: ?>
                <div style="background-color: var(--color-warning-bg); border-radius: var(--radius-btn); padding: 14px; margin-bottom: 16px;">
                    <div style="color: var(--color-warning-text); font-weight: 700;">⚠ Pendiente de Cobro</div>
                    <div style="font-size: 13px; color: var(--color-warning-text); margin-top: 2px;">
                        Registre el cobro para poder entregar el pedido (RN-04).
                    </div>
                </div>

                <?php if ($pedido['estado'] !== 'cancelado'): ?>
                    <form action="index.php?accion=pedidos/pagar" method="POST">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                        <div class="form-group">
                            <label class="form-label" for="metodo_pago">Método de Pago:</label>
                            <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta (Débito/Crédito)</option>
                                <option value="transferencia">Transferencia bancaria</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Registrar Cobro (<?= formatearDinero($pedido['total']) ?>)
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Tarjeta de Flujo y Acciones -->
        <?php if ($pedido['estado'] !== 'entregado' && $pedido['estado'] !== 'cancelado'): ?>
            <div class="card">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">Acciones del Pedido</h3>
                
                <!-- Botón de avanzar estado -->
                <form action="index.php?accion=pedidos/avanzar" method="POST" style="margin-bottom: 10px;">
                    <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                    <?php if ($pedido['estado'] === 'pendiente'): ?>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Pasar a Preparación (Alistado) &rarr;
                        </button>
                    <?php elseif ($pedido['estado'] === 'alistado'): ?>
                        <button type="submit" class="btn btn-lime" style="width: 100%;" <?= empty($pedido['metodo_pago']) ? 'disabled title="Requiere pago previo"' : '' ?>>
                            ✓ Marcar como Entregado
                        </button>
                    <?php endif; ?>
                </form>

                <!-- Botón de cancelar pedido (solo sin pago) -->
                <?php if (empty($pedido['metodo_pago'])): ?>
                    <form action="index.php?accion=pedidos/cancelar" method="POST" class="form-eliminar">
                        <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            Cancelar Pedido y Devolver Stock
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../plantilla/pie.php'; ?>
