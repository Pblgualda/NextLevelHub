<?php
namespace NextLevelHub\Controllers;

use NextLevelHub\Core\AdminMiddleware;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Usuario;
use NextLevelHub\Services\UsuarioService;

class UsuarioController
{
    private UsuarioService $service;
    private Pages $pages;

    // Inicializa el controlador y verifica permisos de admin
    public function __construct()
    {
        AdminMiddleware::handle();
        $db = BaseDatos::getInstancia();
        $this->service = new UsuarioService($db);
        $this->pages = new Pages();
    }

    // Obtiene y muestra la lista de todos los usuarios
    public function listar(): void
    {
        $usuarios = $this->service->findAll();
        $this->pages->render('usuario/showUsuarios', [
            'usuarios' => $usuarios
        ]);
    }

    // Gestiona el formulario para crear o editar un usuario
    public function formUsuario(?int $id = null): void
    {
        $usuario = null;
        $errors = [];
        $data = [];

        if ($id !== null && $id > 0) {
            $usuario = $this->service->findById($id);
            if (!$usuario) {
                $_SESSION['errors'] = ['Usuario no encontrado.'];
                header('Location: ' . BASE_URL . 'usuario/lista');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            if (empty($data['nombre'])) {
                $errors[] = 'El nombre es requerido.';
            }

            if (empty($data['apellidos'])) {
                $errors[] = 'Los apellidos son requeridos.';
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Debe proporcionar un email válido.';
            }

            if ($id === null && (empty($data['password']) || strlen($data['password']) < 6)) {
                $errors[] = 'La contraseña es requerida y debe tener al menos 6 caracteres.';
            }

            if (!isset($data['rol']) || !in_array($data['rol'], ['usuario', 'admin'], true)) {
                $errors[] = 'Debe seleccionar un rol válido para el usuario.';
            }

            if (!empty($errors)) {
                $usuarioParcial = Usuario::fromArray($data);
                $this->pages->render('usuario/formUsuario', [
                    'usuario' => $usuarioParcial,
                    'errores' => $errors,
                    'id' => $id,
                ]);
                return;
            }

            try {
                if ($id !== null && $id > 0) {
                    $existing = $this->service->findById($id);
                    if (!$existing) {
                        throw new \RuntimeException('Usuario no encontrado para editar.');
                    }

                    if (!empty($data['password'])) {
                        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    } else {
                        $data['password'] = $existing->getPassword();
                    }

                    $usuarioActualizado = Usuario::fromArray($data);
                    $usuarioActualizado->setId($id);
                    $usuarioActualizado->setCreatedAt($existing->getCreatedAt());
                    $usuarioActualizado->setUpdatedAt(date('Y-m-d H:i:s'));
                    $this->service->save($usuarioActualizado);
                    $resultado = 'Usuario actualizado con éxito.';
                } else {
                    $usuarioNuevo = Usuario::fromArray($data);
                    $usuarioNuevo->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
                    $usuarioNuevo->setCreatedAt(date('Y-m-d H:i:s'));
                    $usuarioNuevo->setUpdatedAt(date('Y-m-d H:i:s'));
                    $this->service->save($usuarioNuevo);
                    $resultado = 'Usuario creado con éxito.';
                }

                $this->pages->render('usuario/resultado', [
                    'resultado' => $resultado
                ]);
                return;
            } catch (\Exception $e) {
                $usuarioParcial = Usuario::fromArray($data);
                $this->pages->render('usuario/formUsuario', [
                    'usuario' => $usuarioParcial,
                    'errores' => [$e->getMessage()],
                    'id' => $id,
                ]);
                return;
            }
        }

        $this->pages->render('usuario/formUsuario', [
            'usuario' => $usuario,
            'errores' => $errors,
            'id' => $id,
        ]);
    }

    // Elimina un usuario por su ID
    public function eliminar(int $id): void
    {
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID de usuario inválido.'];
            header('Location: ' . BASE_URL . 'usuario/lista');
            exit;
        }

        try {
            $this->service->delete($id);
            $_SESSION['user_delete'] = 'complete';
        } catch (\RuntimeException $e) {
            $_SESSION['errors'] = ["Error al eliminar el usuario: {$e->getMessage()}"];
        }

        header('Location: ' . BASE_URL . 'usuario/lista');
        exit;
    }
}
