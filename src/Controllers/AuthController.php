<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use Google\Client as GoogleClient;
use NextLevelHub\Services\EmailService;
use NextLevelHub\Services\UsuarioService;
use NextLevelHub\Request\LoginRequest;
use NextLevelHub\Request\UserRequest;

class AuthController
{
    private UsuarioService $usuarioService;
    private EmailService $emailService;
    private Pages $pages;

    public function __construct()
    {
        $db = new BaseDatos();
        $this->usuarioService = new UsuarioService($db);
        $this->emailService = new EmailService($db);
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

    public function forgotPassword(): void
    {
        $this->pages->render('auth/forgot_password');
    }

    public function sendPasswordReset(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors'] = ['Introduce un email valido.'];
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        try {
            $usuario = $this->usuarioService->createPasswordResetToken($email);
            if ($usuario && !$this->emailService->sendPasswordReset($usuario, $email)) {
                $_SESSION['errors'] = ['No se pudo enviar el correo de recuperacion. Intentalo mas tarde.'];
                header('Location: ' . BASE_URL . 'auth/recuperar');
                return;
            }

            $_SESSION['password_reset'] = 'sent';
            header('Location: ' . BASE_URL . 'auth/login');
        } catch (\Throwable $e) {
            $_SESSION['errors'] = ['No se pudo iniciar la recuperacion. Intentalo mas tarde.'];
            header('Location: ' . BASE_URL . 'auth/recuperar');
        }
    }

    public function resetPassword(): void
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $_SESSION['errors'] = ['Token de recuperacion no valido.'];
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        $usuario = $this->usuarioService->findByToken($token);
        if (!$usuario || ($usuario->getTokenExp() !== null && strtotime($usuario->getTokenExp()) < time())) {
            $_SESSION['errors'] = ['El enlace de recuperacion no es valido o ha caducado.'];
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        $this->pages->render('auth/reset_password', [
            'token' => $token,
        ]);
    }

    public function updatePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token)) {
            $_SESSION['errors'] = ['Token de recuperacion no valido.'];
            header('Location: ' . BASE_URL . 'auth/recuperar');
            return;
        }

        if (strlen($password) < 8) {
            $_SESSION['errors'] = ['La contrasena debe tener al menos 8 caracteres.'];
            header('Location: ' . BASE_URL . 'auth/restablecer?token=' . urlencode($token));
            return;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['errors'] = ['Las contrasenas no coinciden.'];
            header('Location: ' . BASE_URL . 'auth/restablecer?token=' . urlencode($token));
            return;
        }

        try {
            $this->usuarioService->resetPasswordWithToken($token, $password);
            $_SESSION['password_reset'] = 'complete';
            header('Location: ' . BASE_URL . 'auth/login');
        } catch (\Throwable $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: ' . BASE_URL . 'auth/recuperar');
        }
    }

    public function confirmarEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $_SESSION['errors'] = ['Token de confirmación no válido.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        try {
            $usuario = $this->usuarioService->findByToken($token);
            if (!$usuario) {
                $_SESSION['errors'] = ['El enlace de confirmación no es válido.'];
                header('Location: ' . BASE_URL . 'auth/login');
                return;
            }

            if ($usuario->getConfirmado() == true) {
                $_SESSION['register'] = 'already_confirmed';
                header('Location: ' . BASE_URL . 'auth/login');
                return;
            }

            if ($usuario->getTokenExp() != null && strtotime($usuario->getTokenExp()) < time()) {
                $_SESSION['errors'] = ['El enlace de confirmación ha caducado. Solicita uno nuevo.'];
                header('Location: ' . BASE_URL . 'auth/login');
                return;
            }

            $usuario->setConfirmado(true);
            $usuario->setToken(null);
            $usuario->setTokenExp(null);
            $usuario->setUpdatedAt(date('Y-m-d H:i:s'));

            $this->usuarioService->save($usuario);
            $_SESSION['register'] = 'confirmed';
            header('Location: ' . BASE_URL . 'auth/login');
        } catch (\Throwable $e) {
            $_SESSION['errors'] = ['No se pudo confirmar la cuenta. Inténtalo más tarde.'];
            header('Location: ' . BASE_URL . 'auth/login');
        }
    }

    public function googleLogin(): void
    {
        $client = $this->createGoogleClient();
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    }

    public function googleCallback(): void
    {
        if (!isset($_GET['code'])) {
            $_SESSION['errors'] = ['No se recibió el código de autorización de Google.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        $client = $this->createGoogleClient();
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            $_SESSION['errors'] = ['Error en la autenticación con Google: ' . htmlspecialchars($token['error_description'] ?? $token['error'])];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        $payload = $client->verifyIdToken($token['id_token']);
        if (!$payload || !isset($payload['email'])) {
            $_SESSION['errors'] = ['No se pudo verificar el usuario de Google.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
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
            return;
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
            return;
        }

        header('Location: ' . BASE_URL);
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
            return;
        }

        $loginRequest = new LoginRequest($_POST);

        if (!$loginRequest->validate()) {
            $_SESSION['errors'] = $loginRequest->getErrors();
            header('Location: ' . BASE_URL . 'auth/login');
            return;
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
                return;
            }

            header('Location: ' . BASE_URL);
        } catch (\Throwable $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: ' . BASE_URL . 'auth/login');
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
            return;
        }

        // Crear instancia de UserRequest y validar los datos
        $userRequest = new UserRequest($_POST);

        if (!$userRequest->validate()) {
            // Si hay errores, guardarlos en sesión
            $_SESSION['errors'] = $userRequest->getErrors();
            header('Location: ' . BASE_URL . 'auth/register');
            return;
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
                $usuario = $this->usuarioService->findByEmail($data['email']);
                $emailSent = $usuario
                    ? $this->emailService->sendRegistrationConfirmation($usuario, $usuario->getEmail())
                    : false;

                if (!$emailSent) {
                    $_SESSION['errors'] = ['Tu cuenta se ha creado, pero no se pudo enviar el correo de confirmacion. Contacta con soporte.'];
                    header('Location: ' . BASE_URL . 'auth/register');
                    return;
                }
                $_SESSION['register'] = 'pending_confirmation';
                header('Location: ' . BASE_URL . 'auth/login');
            } else {
                $_SESSION['errors'] = ['Error al registrar el usuario. Intenta mas tarde.'];
                header('Location: ' . BASE_URL . 'auth/register');
            }
        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            header('Location: ' . BASE_URL . 'auth/register');
        }
    }

    public function logout(): void
    {
        unset($_SESSION['identity']);
        session_destroy();
        header('Location: ' . BASE_URL);
    }

    public function profile(): void
    {
        $identity = $_SESSION['identity'] ?? null;

        if ($identity) {
            $this->pages->render('auth/profile', [
                'identity' => $identity,
            ]);
        } else {
            header('Location: ' . BASE_URL . 'auth/login');
        }
    }
}
