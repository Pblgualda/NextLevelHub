<main>
    <h2><?= htmlspecialchars($titulo ?? 'Listado de Productos') ?></h2>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="error-message">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['product_save'])): ?>
        <div class="success-message">Producto guardado correctamente.</div>
        <?php unset($_SESSION['product_save']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['product_delete'])): ?>
        <div class="success-message">Producto eliminado correctamente.</div>
        <?php unset($_SESSION['product_delete']); ?>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>producto/nuevo" class="btn">+ Nuevo Producto</a>

    <?php if (!empty($productos)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto->getId()); ?></td>
                        <td><?= htmlspecialchars($producto->getNombre()); ?></td>
                        <td><?= htmlspecialchars($producto->getDescripcion()); ?></td>
                        <td><?= htmlspecialchars(number_format($producto->getPrecio(), 2)); ?> €</td>
                        <td><?= htmlspecialchars($producto->getStock()); ?></td>
                        <td><?= htmlspecialchars($producto->getCategoriaNombre() ?: $producto->getCategoriaId()); ?></td>
                        <td>
                            <a class="action-link" href="<?= BASE_URL ?>producto/form/<?= (int)$producto->getId(); ?>">Editar</a>
                            <?php if ($producto->getActivo()==1): ?>
                            <a class="action-link danger" href="<?= BASE_URL ?>producto/eliminar/<?= (int)$producto->getId(); ?>">Descatalogar</a>
                            <?php else: ?>
                            <a class="action-link danger" href="<?= BASE_URL ?>producto/activar/<?= (int)$producto->getId(); ?>">Activar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</main>
