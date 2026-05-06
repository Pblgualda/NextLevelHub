<main>
    <h2><?= htmlspecialchars($titulo ?? 'Productos por categoría') ?></h2>

    <?php if ($categoria): ?>
        <p>Mostrando productos de la categoría: <strong><?= htmlspecialchars($categoria->getNombre()) ?></strong></p>
    <?php endif; ?>

    <?php if (!empty($productos)): ?>
        <div class="public-product-grid">
            <?php foreach ($productos as $producto): ?>
                <article class="product-card">
                    <?php if ($producto->getImagen()): ?>
                        <img src="<?= BASE_URL ?>uploads/images/<?= htmlspecialchars($producto->getImagen()) ?>" alt="<?= htmlspecialchars($producto->getNombre()) ?>">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($producto->getNombre()) ?></h3>
                    <p><?= htmlspecialchars($producto->getDescripcion()) ?></p>
                    <p><strong>Precio:</strong> <?= htmlspecialchars(number_format($producto->getPrecio(), 2)) ?> €</p>
                    <?php if ($producto->getPrecioOferta() !== null): ?>
                        <p><strong>Oferta:</strong> <?= htmlspecialchars(number_format($producto->getPrecioOferta(), 2)) ?> €</p>
                    <?php endif; ?>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($producto->getStock()) ?></p>
                    <form class="add-to-cart-form" action="<?= BASE_URL ?>carrito/add" method="post">
                        <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto->getId()) ?>">
                        <label>
                            Cantidad:
                            <input type="number" name="cantidad" min="1" value="1" max="<?= htmlspecialchars($producto->getStock()) ?>" style="width: 70px;">
                        </label>
                        <button type="submit" class="btn">Añadir al carrito</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos disponibles en esta categoría.</p>
    <?php endif; ?>

    <div class="public-category-actions">
        <a class="btn" href="<?= BASE_URL ?>">Volver al inicio</a>
        <a class="btn-secondary" href="<?= BASE_URL ?>categoria/listar">Ver categorías</a>
    </div>
</main>
