<main>
    <h2><?= isset($id) ? 'Editar Producto' : 'Nuevo Producto' ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>producto/form<?= isset($id) ? '/' . (int)$id : '' ?>" enctype="multipart/form-data">
        <label for="categoria_id">Categoría</label>
        <select name="data[categoria_id]" id="categoria_id" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= htmlspecialchars($categoria->getId()); ?>"
                    <?= isset($data['categoria_id']) && $data['categoria_id'] == $categoria->getId() ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($categoria->getNombre()); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nombre">Nombre</label>
        <input
            type="text"
            id="nombre"
            name="data[nombre]"
            value="<?= htmlspecialchars($data['nombre'] ?? ''); ?>"
            required
        >

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="data[descripcion]" rows="4"><?= htmlspecialchars($data['descripcion'] ?? ''); ?></textarea>

        <label for="precio">Precio</label>
        <input
            type="number"
            step="0.01"
            id="precio"
            name="data[precio]"
            value="<?= htmlspecialchars($data['precio'] ?? ''); ?>"
            required
        >

        <label for="precio_oferta">Precio de oferta</label>
        <input
            type="number"
            step="0.01"
            id="precio_oferta"
            name="data[precio_oferta]"
            value="<?= htmlspecialchars($data['precio_oferta'] ?? ''); ?>"
        >

        <label for="stock">Stock</label>
        <input
            type="number"
            id="stock"
            name="data[stock]"
            value="<?= htmlspecialchars($data['stock'] ?? ''); ?>"
            required
        >

        <label for="imagen">Imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <button type="submit">Guardar producto</button>
    </form>
</main>
