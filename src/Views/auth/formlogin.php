<?php
// Obtener errores de la sesión si existen
$errors = $_SESSION['errors'] ?? [];

// Limpiar los errores de la sesión después de mostrarlos
unset($_SESSION['errors']);
?>

    <div class="container-user-form">
        <h1>Iniciar Sesión</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a class="btn-google" href="<?= BASE_URL ?>auth/google">Iniciar sesión con Google</a>

        <form method="POST" action="<?= BASE_URL ?>auth/login">
            
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                    required
                >
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
            </div>

            <!-- Botón -->
            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="link-register">
            ¿No tienes cuenta? <a href="<?=BASE_URL?>/auth/register">Regístrate aquí</a>
        </div>
    </div>
