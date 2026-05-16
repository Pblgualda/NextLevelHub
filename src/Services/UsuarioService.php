<?php


namespace NextLevelHub\Services;

use NextLevelHub\Models\Usuario;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\UsuarioRepository;
use NextLevelHub\Repositories\PedidoRepository;
use NextLevelHub\Repositories\LineaPedidoRepository;

class UsuarioService
{
    private BaseDatos $db;
    private UsuarioRepository $repository;
    private PedidoRepository $pedidoRepository;
    private LineaPedidoRepository $lineaPedidoRepository;

    public function __construct(BaseDatos $db)
    {
        $this->db = $db;
        $this->repository = new UsuarioRepository($db);
        $this->pedidoRepository = new PedidoRepository($db);
        $this->lineaPedidoRepository = new LineaPedidoRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function findById(int $id): ?Usuario
    {
        return $this->repository->findById($id);
    }

    public function save(Usuario $usuario): bool
    {
        return $this->repository->save($usuario);
    }

    public function update(Usuario $usuario): bool
    {
        return $this->repository->update($usuario);
    }

    public function hasPedidos(int $usuarioId): bool
    {
        return $this->repository->hasPedidos($usuarioId);
    }

    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        try {
            $this->db->iniciarTransaccion();

            $pedidos = $this->pedidoRepository->findByUsuarioId($id);
            foreach ($pedidos as $pedido) {
                $pedidoId = $pedido->getId();
                if ($pedidoId > 0) {
                    $this->lineaPedidoRepository->deleteByPedidoId($pedidoId);
                }
            }

            $this->pedidoRepository->deleteByUsuarioId($id);
            $this->repository->delete($id);

            $this->db->confirmar();
            return true;
        } catch (\Throwable $e) {
            $this->db->revertir();
            throw new \RuntimeException(
                "Error al eliminar el usuario y sus pedidos relacionados: {$e->getMessage()}",
                previous: $e
            );
        }
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

        $token = bin2hex(random_bytes(32));
        $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Crear instancia del modelo Usuario
        $usuario = new Usuario(
            nombre: $nombre,
            apellidos: $apellidos,
            email: $email,
            password: password_hash($password, PASSWORD_BCRYPT),
            rol: 'usuario',
            confirmado: false,
            token: $token,
            token_exp: $tokenExpiration,
            created_at: date('Y-m-d H:i:s'),
            updated_at: date('Y-m-d H:i:s')
        );

        // Guardar el usuario
        return $this->repository->save($usuario);
    }

    public function createUser(string $nombre, string $apellidos, string $email, string $password, string $rol = 'usuario', bool $confirmado = false): bool
    {
        if ($this->repository->findByEmail($email)) {
            throw new \Exception('El email ya está registrado en el sistema.');
        }

        $usuario = new Usuario(
            nombre: $nombre,
            apellidos: $apellidos,
            email: $email,
            password: password_hash($password, PASSWORD_BCRYPT),
            rol: $rol,
            confirmado: $confirmado,
            created_at: date('Y-m-d H:i:s'),
            updated_at: date('Y-m-d H:i:s')
        );

        return $this->repository->save($usuario);
    }

    public function findByEmail(string $email): ?Usuario
    {
        return $this->repository->findByEmail($email);
    }

    /**
     * Autentica un usuario usando email y contraseña
     *
     * @param string $email
     * @param string $password
     * @return Usuario
     * @throws \RuntimeException si el email no existe o la contraseña es incorrecta
     */
    public function authenticate(string $email, string $password): Usuario
    {
        $usuario = $this->repository->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario->getPassword())) {
            throw new \RuntimeException('Email o contraseña incorrectos.');
        }

        return $usuario;
    }
}