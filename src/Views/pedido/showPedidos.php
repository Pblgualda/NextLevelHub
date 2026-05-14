<?php
 $pedidos = $pedidos ?? [];
?>

<div class="pedido-listado-container">
    <h2>Todos los pedidos</h2>

    <?php if (empty($pedidos)): ?>
        <p>No hay pedidos registrados.</p>
        <a href="<?= BASE_URL ?>auth/profile" class="btn-secondary">Volver al perfil</a>
    <?php else: ?>
    <table class="pedido-table">
        <thead>
        <tr>
            <th>Número</th>
            <th>Usuario ID</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Provincia</th>
            <th>Localidad</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= htmlspecialchars((string)$pedido->getId()) ?></td>
                <td><?= htmlspecialchars((string)$pedido->getUsuarioId()) ?></td>
                <td><?= htmlspecialchars($pedido->getFechaPedido()) ?></td>
                <td><?= htmlspecialchars(ucfirst($pedido->getEstado())) ?></td>
                <td><?= htmlspecialchars($pedido->getProvincia()) ?></td>
                <td><?= htmlspecialchars($pedido->getLocalidad()) ?></td>
                <td><?= htmlspecialchars(number_format($pedido->getCosteTotal(), 2)) ?> €</td>
                <td>
                    <a class="btn" href="<?= BASE_URL ?>pedido/ver/<?= htmlspecialchars((string)$pedido->getId()) ?>">
                        Ver detalle
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>

    <div class="admin-links">
        <a class="btn-secondary" href="<?= BASE_URL ?>auth/profile">Volver al perfil</a>
    </div>
    <?php endif; ?>
</div>



