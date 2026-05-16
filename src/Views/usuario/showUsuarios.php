<?php
//vista de todos los usuarios desde un admin


$usuarios = $usuarios ?? [];
?>
<div class="container-user-list">
    <h1>Lista de usuarios</h1>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="error-message">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['user_delete'])): ?>
        <div class="success-message">Usuario eliminado correctamente.</div>
        <?php unset($_SESSION['user_delete']); ?>
    <?php endif; ?>

    <?php if (empty($usuarios)): ?>
        <p>No hay usuarios registrados.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario->getId()) ?></td>
                        <td><?= htmlspecialchars($usuario->getNombre()) ?></td>
                        <td><?= htmlspecialchars($usuario->getApellidos()) ?></td>
                        <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                        <td><?= htmlspecialchars($usuario->getRol()) ?></td>
                        <td>
                            <a class="action-link" href="<?= BASE_URL ?>usuario/form/<?= (int)$usuario->getId() ?>">Editar</a>
                            <a class="action-link danger" href="<?= BASE_URL ?>usuario/eliminar/<?= (int)$usuario->getId() ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="admin-links">
        <a class="btn" href="<?= BASE_URL ?>usuario/nuevo">Crear nuevo usuario</a>
        <a class="btn-secondary" href="<?= BASE_URL ?>auth/profile">Volver al perfil</a>
    </div>
</div>
