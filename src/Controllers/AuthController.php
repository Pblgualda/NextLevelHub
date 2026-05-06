<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use Google\Client as GoogleClient;
use NextLevelHub\Services\UsuarioService;
use NextLevelHub\Request\LoginRequest;
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
        //header('Location: ' . BASE_URL);
    }

    public function login(): void
    {
        $this->pages->render('auth/formlogin');
    }

    public function googleLogin(): void
    {
        $client = $this->createGoogleClient();
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }

    public function googleCallback(): void
    {
        if (!isset($_GET['code'])) {
            $_SESSION['errors'] = ['No se recibió el código de autorización de Google.'];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $client = $this->createGoogleClient();
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            $_SESSION['errors'] = ['Error en la autenticación con Google: ' . htmlspecialchars($token['error_description'] ?? $token['error'])];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $payload = $client->verifyIdToken($token['id_token']);
        if (!$payload || !isset($payload['email'])) {
            $_SESSION['errors'] = ['No se pudo verificar el usuario de Google.'];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $email = $payload['email'];
        $nombre = $payload['given_name'] ?? '';
        $apellidos = $payload['family_name'] ?? '';

        $usuario = $this->usuarioService->findByEmail($email);
        if (!$usuario) {
            $password = bin2hex(random_bytes(16));
            $this->usuarioService->createUser($nombre, $apellidos, $email, $password, 'usuario', true);
            $usuario = $this->usuarioService->findByEmail($email);
        }

        if (!$usuario) {
            $_SESSION['errors'] = ['No se pudo iniciar sesión con Google.'];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $_SESSION['identity'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'apellidos' => $usuario->getApellidos(),
            'email' => $usuario->getEmail(),
            'rol' => $usuario->getRol(),
        ];

        $redirect = $_SESSION['cart_redirect'] ?? null;
        if ($redirect) {
            unset($_SESSION['cart_redirect']);
            header('Location: ' . $redirect);
            exit;
        }

        header('Location: ' . BASE_URL);
        exit;
    }

    private function createGoogleClient(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');
        $client->setAccessType('offline');
        $client->setPrompt('select_account');
        return $client;
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $loginRequest = new LoginRequest($_POST);

        if (!$loginRequest->validate()) {
            $_SESSION['errors'] = $loginRequest->getErrors();
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $data = $loginRequest->getData();

        try {
            $usuario = $this->usuarioService->authenticate($data['email'], $data['password']);
            $_SESSION['identity'] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol(),
            ];

            $redirect = $_SESSION['cart_redirect'] ?? null;
            if ($redirect) {
                unset($_SESSION['cart_redirect']);
                header('Location: ' . $redirect);
                exit;
            }

            header('Location: ' . BASE_URL);
            exit;
        } catch (\Throwable $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    /**
     * Procesa los datos del formulario de registro
     */
    public function save(): void
    {
        // Solo procesar peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'auth/register');
            exit;
        }

        // Crear instancia de UserRequest y validar los datos
        $userRequest = new UserRequest($_POST);

        if (!$userRequest->validate()) {
            // Si hay errores, guardarlos en sesión
            $_SESSION['errors'] = $userRequest->getErrors();
            header('Location: ' . BASE_URL . 'auth/register');
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
                $redirect = $_SESSION['cart_redirect'] ?? null;
                if ($redirect) {
                    unset($_SESSION['cart_redirect']);
                    header('Location: ' . $redirect);
                    exit;
                }

                header('Location: ' . BASE_URL);
                exit;
            } else {
                $_SESSION['errors'] = ['Error al registrar el usuario. Intenta más tarde.'];
                header('Location: ' . BASE_URL . 'auth/register');
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: ' . BASE_URL . 'auth/register');
            exit;
        }
    }

    public function logout(): void
    {
        unset($_SESSION['identity']);
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

    public function profile(): void
    {
        $identity = $_SESSION['identity'] ?? null;

        if (!$identity) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $this->pages->render('auth/profile', [
            'identity' => $identity,
        ]);
    }
}
