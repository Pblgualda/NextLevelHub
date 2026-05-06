<?php
// Obtener errores de la sesión si existen
$errors = $_SESSION['errors'] ?? [];

// Limpiar los errores de la sesión después de mostrarlos
unset($_SESSION['errors']);
?>

    <div class="container-user-form">
        <h1>Crear Cuenta</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <h3>Por favor corrige los siguientes errores:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?=BASE_URL?>auth/save" id="formRegistro">
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Tu nombre"
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                    required
                >
            </div>

            <!-- Campo Apellidos -->
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input 
                    type="text" 
                    id="apellidos" 
                    name="apellidos" 
                    placeholder="Tus apellidos"
                    value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>"
                    required
                >
            </div>

            <!-- Campo Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>

            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Mínimo 8 caracteres"
                    required
                >
                <div class="password-match-hint">
                    Mínimo 8 caracteres (usar mayúsculas, números y símbolos para mayor seguridad)
                </div>
            </div>

            <!-- Campo Confirmar Contraseña -->
            <div class="form-group">
                <label for="password_confirm">Confirmar Contraseña</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    placeholder="Repite tu contraseña"
                    required
                >
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="btn">Registrarse</button>
        </form>

        <!-- Enlace a Login -->
        <div class="link-register">
            ¿Ya tienes cuenta? <a href="<?=BASE_URL?>/auth/login">Inicia sesión aquí</a>
        </div>
    </div>
