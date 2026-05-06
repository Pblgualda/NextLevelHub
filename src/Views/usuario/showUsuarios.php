<?php
$usuarios = $usuarios ?? [];
?>
<div class="container-user-list">
    <h1>Lista de usuarios</h1>

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
                            <a class="action-link danger" href="<?= BASE_URL ?>usuario/eliminar/<?= (int)$usuario->getId() ?>">Eliminar</a>
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
