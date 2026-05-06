<?php
$identity = $identity ?? $_SESSION['identity'] ?? null;
?>
<div class="container-profile">
    <h1>Mi perfil</h1>

    <?php if (!$identity): ?>
        <p>No hay información de usuario disponible.</p>
    <?php else: ?>
        <div class="profile-card">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($identity['nombre'] ?? '') ?></p>
            <p><strong>Apellidos:</strong> <?= htmlspecialchars($identity['apellidos'] ?? '') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($identity['email'] ?? '') ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($identity['rol'] ?? '') ?></p>
        </div>

        <?php if (($identity['rol'] ?? '') === 'admin'): ?>
            <section class="admin-actions">
                <h2>Acciones de administrador</h2>
                <div class="button-group">
                    <a class="btn" href="<?= BASE_URL ?>producto/nuevo">Agregar producto</a>
                    <a class="btn" href="<?= BASE_URL ?>producto/gestion">Gestionar productos</a>
                    <a class="btn" href="<?= BASE_URL ?>categoria/form">Agregar categoría</a>
                    <a class="btn" href="<?= BASE_URL ?>categoria/listar">Gestionar categorías</a>
                    <a class="btn" href="<?= BASE_URL ?>usuario/nuevo">Crear usuario</a>
                    <a class="btn" href="<?= BASE_URL ?>usuario/lista">Ver usuarios</a>
                </div>
            </section>
        <?php endif; ?>

        <section class="customer-actions">
            <h2>Mis pedidos</h2>
            <div class="button-group">
                <a class="btn-secondary" href="<?= BASE_URL ?>pedido/mis-pedidos">Ver mis pedidos</a>
            </div>
        </section>

        <div class="profile-footer">
            <a class="btn-secondary" href="<?= BASE_URL ?>">Volver al inicio</a>
        </div>
    <?php endif; ?>
</div>
