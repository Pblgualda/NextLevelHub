<?php
// Obtener errores de la sesión si existen
$errors = $_SESSION['errors'] ?? [];

// Limpiar los errores de la sesión después de mostrarlos
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente - NextLevelHub</title>
    <link rel="stylesheet" href="/css/index.css">
    <style>
        .container-registro {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .container-registro h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .form-group input.error {
            border-color: #dc3545;
        }

        .form-group input.error:focus {
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .btn-submit:active {
            background-color: #004085;
        }

        .error-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
        }

        .error-container h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .error-container ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-container li {
            margin-bottom: 5px;
        }

        .link-login {
            text-align: center;
            margin-top: 20px;
        }

        .link-login a {
            color: #007bff;
            text-decoration: none;
        }

        .link-login a:hover {
            text-decoration: underline;
        }

        .password-match-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-registro">
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

        <form method="POST" action="/auth/save" id="formRegistro">
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
            <button type="submit" class="btn-submit">Registrarse</button>
        </form>

        <!-- Enlace a Login -->
        <div class="link-login">
            ¿Ya tienes cuenta? <a href="/auth/login">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
