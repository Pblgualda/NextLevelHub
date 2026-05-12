<main>
    <h2>Tu carrito de la compra</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($stockWarnings)): ?>
        <?php foreach ($stockWarnings as $warning): ?>
            <div class="alert alert-warning"><?= htmlspecialchars($warning) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($items)): ?>
        <table class="carrito-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $producto = $item['producto']; ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($producto->getNombre()) ?></strong><br>
                            <?= htmlspecialchars($producto->getDescripcion()) ?><br>
                            <small>Stock: <?= htmlspecialchars($producto->getStock()) ?></small>
                        </td>
                        <td><?= htmlspecialchars(number_format($item['precio'], 2)) ?> €</td>
                        <td><?= htmlspecialchars($item['cantidad']) ?></td>
                        <td><?= htmlspecialchars(number_format($item['subtotal'], 2)) ?> €</td>
                        <td class="cart-actions">
                            <a class="btn-small" href="<?= BASE_URL ?>carrito/increment/<?= $producto->getId() ?>">+</a>
                            <a class="btn-small" href="<?= BASE_URL ?>carrito/decrement/<?= $producto->getId() ?>">-</a>
                            <a class="btn-small btn-danger" href="<?= BASE_URL ?>carrito/remove/<?= $producto->getId() ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total artículos:</strong></td>
                    <td colspan="2"><?= htmlspecialchars($totalQuantity) ?></td>
                    <td colspan="2"><strong>Total:</strong> <?= htmlspecialchars(number_format($totalCost, 2)) ?> €</td>
                </tr>
            </tfoot>
        </table>

        <div class="cart-buttons">
            <a class="btn" href="<?= BASE_URL ?>carrito/vaciar">Vaciar carrito</a>
            <a class="btn btn-primary" href="<?= BASE_URL ?>carrito/confirm">Finalizar pedido</a>
        </div>
    <?php else: ?>
        <p>Tu carrito está vacío. Añade productos desde la página de categorías.</p>
        <div class="cart-buttons">
            <a class="btn" href="<?= BASE_URL ?>">Volver al inicio</a>
        </div>
    <?php endif; ?>
</main>

<style>
    .carrito-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
    }

    .carrito-table th,
    .carrito-table td {
        padding: 14px 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .carrito-table th {
        background: #f8f9fa;
    }

    .cart-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .cart-buttons {
        margin-top: 18px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-small {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        background: #007bff;
        color: #fff;
        text-decoration: none;
    }

    .btn-danger {
        background: #dc3545;
    }

    .btn-primary {
        background: #28a745;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
    }
</style>
