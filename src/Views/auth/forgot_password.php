<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<div class="container-user-form">
    <h1>Recuperar contrasena</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-container">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>auth/recuperar">
        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
            >
        </div>

        <button type="submit" class="btn">Enviar enlace</button>
    </form>

    <div class="link-register">
        <a href="<?= BASE_URL ?>auth/login">Volver al inicio de sesion</a>
    </div>
</div>
