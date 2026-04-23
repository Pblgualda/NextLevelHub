<?php


namespace NextLevelHub\Services;

use NextLevelHub\Models\Usuario;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\UsuarioRepository;

class UsuarioService
{
    private UsuarioRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new UsuarioRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function save(Usuario $usuario): bool
    {
        return $this->repository->save($usuario);
    }

    public function update(Usuario $usuario): bool
    {
        return $this->repository->update($usuario);
    }

    /**
     * Registra un nuevo usuario en el sistema
     * 
     * Valida que el usuario no exista, encripta la contraseña y guarda el usuario en la base de datos
     * 
     * @param string $nombre Nombre del usuario
     * @param string $apellidos Apellidos del usuario
     * @param string $email Email del usuario
     * @param string $password Contraseña sin encriptar
     * 
     * @return bool true si el registro fue exitoso, false en caso contrario
     * @throws \Exception si el email ya existe
     */
    public function register(string $nombre, string $apellidos, string $email, string $password): bool
    {
        // Verificar si el usuario ya existe
        if ($this->repository->findByEmail($email)) {
            throw new \Exception('El email ya está registrado en el sistema.');
        }

        // Crear instancia del modelo Usuario
        $usuario = new Usuario(
            nombre: $nombre,
            apellidos: $apellidos,
            email: $email,
            password: password_hash($password, PASSWORD_BCRYPT),
            rol: 'usuario',
            confirmado: false,
            created_at: date('Y-m-d H:i:s'),
            updated_at: date('Y-m-d H:i:s')
        );

        // Guardar el usuario
        return $this->repository->save($usuario);
    }
}