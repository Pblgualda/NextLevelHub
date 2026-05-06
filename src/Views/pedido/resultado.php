<main>
    <h2>Pedido confirmado</h2>

    <div class="pedido-resumen">
        <p><?= htmlspecialchars($resultado ?? 'Tu pedido se ha procesado correctamente.') ?></p>

        <?php if (isset($pedido)): ?>
            <section class="pedido-detalles">
                <h3>Datos del pedido</h3>
                <p><strong>Número:</strong> <?= htmlspecialchars($pedido->getId()) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($pedido->getEstado()) ?></p>
                <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido->getFechaPedido()) ?></p>
                <p><strong>Provincia:</strong> <?= htmlspecialchars($pedido->getProvincia()) ?></p>
                <p><strong>Localidad:</strong> <?= htmlspecialchars($pedido->getLocalidad()) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido->getDireccion()) ?></p>
            </section>

            <section class="pedido-lineas">
                <h3>Productos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['producto']->getNombre()) ?></td>
                                <td><?= htmlspecialchars(number_format($item['precio'], 2)) ?> €</td>
                                <td><?= htmlspecialchars($item['cantidad']) ?></td>
                                <td><?= htmlspecialchars(number_format($item['subtotal'], 2)) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p><strong>Subtotal:</strong> <?= htmlspecialchars(number_format($pedido->getSubtotal(), 2)) ?> €</p>
                <p><strong>Impuestos:</strong> <?= htmlspecialchars(number_format($pedido->getImpuestos(), 2)) ?> €</p>
                <p><strong>Total:</strong> <?= htmlspecialchars(number_format($pedido->getCosteTotal(), 2)) ?> €</p>
            </section>

            <p class="email-info">
                <?= isset($emailSent) && $emailSent ? 'Se ha enviado un correo de confirmación a tu email.' : 'No se ha podido enviar el correo de confirmación.' ?>
            </p>
        <?php endif; ?>

        <a class="btn" href="<?= BASE_URL ?>">Volver al inicio</a>
    </div>
</main>

<style>
    .pedido-resumen {
        max-width: 780px;
        margin: 24px auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,.08);
    }

    .pedido-detalles,
    .pedido-lineas {
        margin-bottom: 20px;
    }

    .pedido-lineas table {
        width: 100%;
        border-collapse: collapse;
    }

    .pedido-lineas th,
    .pedido-lineas td {
        padding: 12px 10px;
        border: 1px solid #ddd;
    }

    .email-info {
        margin-top: 16px;
        font-weight: 600;
    }

    .btn {
        display: inline-block;
        padding: 12px 18px;
        background: #007bff;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
    }
</style>
