<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Services\UsuarioService;
use NextLevelHub\Request\UserRequest;

class AuthController
{
    private UsuarioService $usuarioService;
    private Pages $pages;

    public function __construct()
    {
        $db = new BaseDatos();
        $this->usuarioService = new UsuarioService($db);
        $this->pages = new Pages();
    }

    /**
     * Renderiza la vista del formulario de registro
     */
    public function register(): void
    {
        $this->pages->render('auth/formregistro');
    }

    /**
     * Procesa los datos del formulario de registro
     */
    public function save(): void
    {
        // Solo procesar peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/register');
            exit;
        }

        // Crear instancia de UserRequest y validar los datos
        $userRequest = new UserRequest($_POST);

        if (!$userRequest->validate()) {
            // Si hay errores, guardarlos en sesión
            $_SESSION['errors'] = $userRequest->getErrors();
            header('Location: /auth/register');
            exit;
        }

        // Obtener los datos sanitizados
        $data = $userRequest->getData();

        // Llamar al servicio para registrar el usuario
        try {
            $result = $this->usuarioService->register(
                $data['nombre'],
                $data['apellidos'],
                $data['email'],
                $data['password']
            );

            if ($result) {
                $_SESSION['register'] = 'success';
                header('Location: /');
                exit;
            } else {
                $_SESSION['errors'] = ['Error al registrar el usuario. Intenta más tarde.'];
                header('Location: /auth/register');
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: /auth/register');
            exit;
        }
    }
}