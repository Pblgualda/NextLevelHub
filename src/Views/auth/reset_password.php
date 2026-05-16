<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<div class="container-user-form">
    <h1>Nueva contrasena</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-container">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>auth/restablecer">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

        <div class="form-group">
            <label for="password">Nueva contrasena</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmar contrasena</label>
            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                minlength="8"
                required
            >
        </div>

        <button type="submit" class="btn">Cambiar contrasena</button>
    </form>
</div>
