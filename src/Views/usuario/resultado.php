<div class="container-result">
    <h1>Resultado</h1>
    <p><?= htmlspecialchars($resultado ?? 'Operación completada.') ?></p>
    <a class="btn" href="<?= BASE_URL ?>auth/profile">Volver al perfil</a>
</div>
<style>
    .container-result {
        max-width: 640px;
        margin: 50px auto;
        padding: 28px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,.08);
        text-align: center;
    }
    .container-result h1 {
        margin-bottom: 18px;
    }
    .btn {
        display: inline-block;
        padding: 12px 18px;
        background: #007bff;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
    }
</style>
