<main>
    <?php if (!empty($_SESSION['identity'])): ?>
        <h2>Bienvenido <?= htmlspecialchars(($_SESSION['identity']['nombre'] ?? '') . ' ' . ($_SESSION['identity']['apellidos'] ?? '')); ?></h2>
    <?php else: ?>
        <h2>Bienvenido a NextLevelHub</h2>
    <?php endif; ?>
    <p>Tienda de videojuegos y merchandising épico</p>
    <h3>Categorías</h3>

    <?php if (!empty($categorias)): ?>
        <ul class="categoria-list">
            <?php foreach ($categorias as $categoria): ?>
                <li>
                    <a href="<?= BASE_URL ?>categoria/productos/<?= (int)$categoria->getId() ?>">
                        <?= htmlspecialchars($categoria->getNombre()) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay categorías disponibles aún.</p>
    <?php endif; ?>

    <section class="home-products">
        <h3>Productos destacados</h3>

        <?php if (!empty($productos)): ?>
            <div class="public-product-grid">
                <?php foreach ($productos as $producto): ?>
                    <article class="product-card">
                        <?php if ($producto->getImagen()): ?>
                            <img src="<?= BASE_URL ?>uploads/images/<?= htmlspecialchars($producto->getImagen()) ?>" alt="<?= htmlspecialchars($producto->getNombre()) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($producto->getNombre()) ?></h3>
                        <p><?= htmlspecialchars($producto->getDescripcion()) ?></p>
                        <p><strong>Precio:</strong> <?= htmlspecialchars(number_format($producto->getPrecio(), 2)) ?> EUR</p>
                        <?php if ($producto->getPrecioOferta() !== null): ?>
                            <p><strong>Oferta:</strong> <?= htmlspecialchars(number_format($producto->getPrecioOferta(), 2)) ?> EUR</p>
                        <?php endif; ?>
                        <p><strong>Stock:</strong> <?= htmlspecialchars($producto->getStock()) ?></p>
                        <?php if ($producto->getStock() > 0): ?>
                            <form class="add-to-cart-form" action="<?= BASE_URL ?>carrito/add" method="post">
                                <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto->getId()) ?>">
                                <label>
                                    Cantidad:
                                    <input type="number" name="cantidad" min="1" value="1" max="<?= htmlspecialchars($producto->getStock()) ?>" style="width: 70px;">
                                </label>
                                <button type="submit" class="btn">AÃ±adir al carrito</button>
                            </form>
                        <?php else: ?>
                            <p>Sin stock disponible.</p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay productos disponibles todavÃ­a.</p>
        <?php endif; ?>
    </section>
</main>
