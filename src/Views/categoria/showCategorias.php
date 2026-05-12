<?php
$categorias = $categorias ?? [];
?>
<div class="container-category-list">
    <h1>Gestión de categorías</h1>

    <div class="actions-bar">
        <a class="btn" href="<?= BASE_URL ?>categoria/form">Crear categoría</a>
        <a class="btn-secondary" href="<?= BASE_URL ?>auth/profile">Volver al perfil</a>
    </div>

    <?php if (empty($categorias)): ?>
        <p>No hay categorías registradas.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Creada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars($categoria->getId()) ?></td>
                        <td><?= htmlspecialchars($categoria->getNombre()) ?></td>
                        <td><?= htmlspecialchars($categoria->getDescripcion()) ?></td>
                        <td><?= htmlspecialchars($categoria->getCreated()) ?></td>
                        <td>
                            <a class="action-link" href="<?= BASE_URL ?>categoria/form/<?= (int)$categoria->getId() ?>">Editar</a>
                            <!--<a class="action-link danger" href="<?= BASE_URL ?>categoria/eliminar/<?= (int)$categoria->getId() ?>">Eliminar</a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<style>
    .container-category-list {
        max-width: 960px;
        margin: 40px auto;
        padding: 28px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
    }
    .container-category-list h1 {
        margin-bottom: 24px;
    }
    .actions-bar {
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn, .btn-secondary {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
    }
    .btn { background: #007bff; }
    .btn-secondary { background: #6c757d; }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px 10px;
        border: 1px solid #eaeaea;
        text-align: left;
    }
    th {
        background: #f7f7f7;
    }
    .action-link {
        margin-right: 12px;
        color: #007bff;
        text-decoration: none;
    }
    .action-link.danger {
        color: #d93025;
    }
</style>
