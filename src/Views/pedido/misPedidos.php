<?php
/** @var array $pedidos */
?>
<div class="pedido-listado-container">
    <h2>Mis pedidos</h2>

    <?php if (empty($pedidos)): ?>
        <p>No tienes pedidos registrados aún.</p>
        <a href="<?= BASE_URL ?>" class="btn-secondary">Volver al inicio</a>
    <?php else: ?>
        <table class="pedido-table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido->getId()) ?></td>
                        <td><?= htmlspecialchars($pedido->getFechaPedido()) ?></td>
                        <td><?= htmlspecialchars(ucfirst($pedido->getEstado())) ?></td>
                        <td><?= htmlspecialchars(number_format($pedido->getCosteTotal(), 2)) ?> €</td>
                        <td>
                            <a class="btn" href="<?= BASE_URL ?>pedido/ver/<?= htmlspecialchars($pedido->getId()) ?>">Ver detalle</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<style>
    .pedido-listado-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    }
    .pedido-listado-container h2 {
        margin-bottom: 24px;
    }
    .pedido-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pedido-table th,
    .pedido-table td {
        padding: 12px 14px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .pedido-table th {
        background: #f8f8f8;
    }
    .btn, .btn-secondary {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        background: #007bff;
    }
    .btn-secondary {
        background: #6c757d;
    }
</style>
