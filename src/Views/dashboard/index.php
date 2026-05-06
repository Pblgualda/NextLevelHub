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
</main>
