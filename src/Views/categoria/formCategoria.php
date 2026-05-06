<?php
$categoria = $categoria ?? null;
$errors = $errors ?? [];
$isEdit = $isEdit ?? false;

function old(string $field, $default = '') {
    global $categoria;
    if ($categoria) {
        $getter = 'get' . ucfirst($field);
        if (method_exists($categoria, $getter)) {
            return htmlspecialchars($categoria->$getter());
        }
    }
    return htmlspecialchars($_POST[$field] ?? $default);
}
?>
<div class="container-category-form">
    <h1><?= $isEdit ? 'Editar categoría' : 'Crear categoría' ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="error-container">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>categoria/form<?= $isEdit ? '/' . (int)$categoria->getId() : '' ?>">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="data[nombre]" value="<?= old('nombre') ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="data[descripcion]" rows="4"><?= old('descripcion') ?></textarea>
        </div>

        <button type="submit" class="btn">Guardar categoría</button>
    </form>

    <div class="back-link">
        <a href="<?= BASE_URL ?>categoria/listar">Volver a la lista de categorías</a>
    </div>
</div>
<style>
    .container-category-form {
        max-width: 680px;
        margin: 40px auto;
        padding: 28px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
    }
    .container-category-form h1 {
        margin-bottom: 24px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    .btn {
        display: inline-block;
        padding: 12px 18px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
    }
    .back-link {
        margin-top: 18px;
    }
    .back-link a {
        color: #007bff;
        text-decoration: none;
    }
    .error-container {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8d7da;
        border-radius: 6px;
        color: #721c24;
    }
</style>
