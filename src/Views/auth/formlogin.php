<?php
// Obtener errores de la sesión si existen
$errors = $_SESSION['errors'] ?? [];
$registerStatus = $_SESSION['register'] ?? null;
$passwordResetStatus = $_SESSION['password_reset'] ?? null;

// Limpiar los errores de la sesión después de mostrarlos
unset($_SESSION['errors']);
unset($_SESSION['register']);
unset($_SESSION['password_reset']);
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

        <?php if ($registerStatus === 'pending_confirmation'): ?>
            <div class="success-container">
                Te hemos enviado un correo de confirmacion. Revisa tu bandeja de entrada y confirma tu cuenta antes de iniciar sesion.
            </div>
        <?php elseif ($registerStatus === 'confirmed'): ?>
            <div class="success-container">
                Tu cuenta se ha confirmado correctamente. Ya puedes iniciar sesion.
            </div>
        <?php elseif ($registerStatus === 'already_confirmed'): ?>
            <div class="success-container">
                Tu cuenta ya estaba confirmada. Puedes iniciar sesion.
            </div>
        <?php endif; ?>

        <a class="btn-google" href="<?= BASE_URL ?>auth/google">Iniciar sesión con Google</a>

        <?php if ($passwordResetStatus === 'sent'): ?>
            <div class="success-container">
                Si el email existe en nuestra tienda, te hemos enviado un enlace para cambiar la contrasena.
            </div>
        <?php elseif ($passwordResetStatus === 'complete'): ?>
            <div class="success-container">
                Tu contrasena se ha actualizado correctamente. Ya puedes iniciar sesion.
            </div>
        <?php endif; ?>

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
        <div class="link-register">
            <a href="<?= BASE_URL ?>auth/recuperar">He olvidado mi contrasena</a>
        </div>
    </div>
