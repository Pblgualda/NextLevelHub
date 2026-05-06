<?php
/** @var \NextLevelHub\Models\Pedido $pedido */
/** @var array $lineas */
?>
<div class="pedido-detalle-container">
    <h2>Detalle del pedido #<?= htmlspecialchars($pedido->getId()) ?></h2>

    <div class="pedido-meta">
        <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido->getFechaPedido()) ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars(ucfirst($pedido->getEstado())) ?></p>
        <p><strong>Provincia:</strong> <?= htmlspecialchars($pedido->getProvincia()) ?></p>
        <p><strong>Localidad:</strong> <?= htmlspecialchars($pedido->getLocalidad()) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido->getDireccion()) ?></p>
    </div>

    <h3>Productos</h3>
    <?php if (empty($lineas)): ?>
        <p>No se han encontrado líneas para este pedido.</p>
    <?php else: ?>
        <table class="pedido-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unidad</th>
                    <th>Total línea</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineas as $linea): ?>
                    <tr>
                        <td><?= htmlspecialchars($linea->getProductoNombre() ?: 'Producto desconocido') ?></td>
                        <td><?= htmlspecialchars($linea->getCantidad()) ?></td>
                        <td><?= htmlspecialchars(number_format($linea->getPrecio(), 2)) ?> €</td>
                        <td><?= htmlspecialchars(number_format($linea->getCantidad() * $linea->getPrecio(), 2)) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="pedido-resumen">
        <p><strong>Subtotal:</strong> <?= htmlspecialchars(number_format($pedido->getSubtotal(), 2)) ?> €</p>
        <p><strong>Impuestos:</strong> <?= htmlspecialchars(number_format($pedido->getImpuestos(), 2)) ?> €</p>
        <p><strong>Total:</strong> <?= htmlspecialchars(number_format($pedido->getCosteTotal(), 2)) ?> €</p>
    </div>

    <div class="detalle-actions">
        <a class="btn-secondary" href="<?= BASE_URL ?>pedido/mis-pedidos">Volver a mis pedidos</a>
    </div>
</div>
<style>
    .pedido-detalle-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    }
    .pedido-meta p,
    .pedido-resumen p {
        margin: 8px 0;
    }
    .pedido-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .pedido-table th,
    .pedido-table td {
        padding: 12px 14px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .pedido-table th {
        background: #f8f8f8;
    }
    .detalle-actions {
        margin-top: 20px;
    }
    .btn-secondary {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        background: #6c757d;
    }
</style>
