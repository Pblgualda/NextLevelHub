<main>
    <h2>Finalizar pedido</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>pedido/nuevo" method="post" class="pedido-form">
        <div class="form-group">
            <label for="provincia">Provincia</label>
            <input type="text" id="provincia" name="data[provincia]" value="<?= htmlspecialchars($data['provincia'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="localidad">Localidad</label>
            <input type="text" id="localidad" name="data[localidad]" value="<?= htmlspecialchars($data['localidad'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección de envío</label>
            <input type="text" id="direccion" name="data[direccion]" value="<?= htmlspecialchars($data['direccion'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn">Confirmar pedido</button>
    </form>

    <div class="pedido-actions">
        <a class="btn-secondary" href="<?= BASE_URL ?>carrito">Volver al carrito</a>
    </div>
</main>

<style>
    .pedido-form {
        max-width: 640px;
        margin: 24px auto;
        display: grid;
        gap: 16px;
    }

    .form-group {
        display: grid;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
    }

    .form-group input {
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        width: 100%;
    }

    .pedido-actions {
        margin-top: 18px;
        display: flex;
        gap: 12px;
    }

    .btn-secondary {
        display: inline-block;
        padding: 12px 18px;
        background: #6c757d;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
    }
</style>
