<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?=BASE_URL?>css/style.css">
    
    <title>Empresa</title>
</head>
<body>
<?php 
    $identity = $_SESSION['identity'] ?? null;
?>
<header>
    <nav>
        <div class="logo">
            <a href="<?=BASE_URL?>"><img src="<?=BASE_URL?>img/logo.png" alt="Logo"></a>
        </div>
        <div class="search">
            <input type="text" placeholder="Buscar...">
            <img src="<?=BASE_URL?>img/lupa.png" alt="Buscar">
        </div>
        <div class="user-session">
            <?php if (!$identity): ?>
                <a href="<?=BASE_URL?>auth/login"><img src="<?=BASE_URL?>img/persona.png" alt="Iniciar sesión"></a>
            <?php else: ?>
                <a class="profile-link" href="<?=BASE_URL?>auth/profile"><img src="<?=BASE_URL?>img/persona.png" alt="Mi perfil"></a>
                <span class="user-welcome">Hola, <?= htmlspecialchars(($identity['nombre'] ?? '') . ' ' . ($identity['apellidos'] ?? '')); ?></span>
                <a href="<?=BASE_URL?>auth/logout">Cerrar sesión</a>
            <?php endif; ?>
        </div>
        <div class="cart">
            <?php $cartCount = array_sum($_SESSION['carrito'] ?? []); ?>
            <a href="<?= BASE_URL ?>carrito" class="cart-link">
                <img src="<?=BASE_URL?>img/cesta.png" alt="Cesta">
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?= htmlspecialchars($cartCount) ?></span>
                <?php endif; ?>
            </a>
        </div>

    </nav>
</header>
