<?php

namespace NextLevelHub\Core;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $identity = $_SESSION['identity'] ?? null;

        if (!$identity || !isset($identity['rol']) || $identity['rol'] !== 'admin') {
            $_SESSION['errors'] = ['Acceso denegado. Debes iniciar sesión como administrador.'];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }
}
