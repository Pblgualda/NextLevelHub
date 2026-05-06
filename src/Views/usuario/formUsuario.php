<?php
$usuario = $usuario ?? null;
$errores = $errores ?? [];

function old(string $field, $default = '') {
    global $usuario;
    if ($usuario) {
        $getter = 'get' . ucfirst($field);
        if (method_exists($usuario, $getter)) {
            return htmlspecialchars($usuario->$getter());
        }
    }
    return htmlspecialchars($_POST[$field] ?? $default);
}
?>
<div class="container-user-form">
    <h1><?= isset($id) ? 'Editar usuario' : 'Crear usuario' ?></h1>

    <?php if (!empty($errores)): ?>
        <div class="error-container">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>usuario/<?= isset($id) ? 'form/' . (int)$id : 'nuevo' ?>">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input type="text" id="apellidos" name="apellidos" value="<?= old('apellidos') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" <?= isset($id) ? '' : 'required' ?> >
            <?php if (isset($id)): ?>
                <small>Dejar en blanco para mantener la contraseña actual.</small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" required>
                <option value="usuario" <?= old('rol', 'usuario') === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= old('rol') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn">Guardar usuario</button>
    </form>

    <div class="back-link">
        <a href="<?= BASE_URL ?>auth/profile">Volver a perfil</a>
    </div>
</div>
