<?php


namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Usuario;
use RuntimeException;
use PDOException;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    public function __construct(
        private readonly BaseDatos $conexion
    )
    {
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM usuarios";
            $this->conexion->ejecutar($sql);

            $usuarios = [];
            foreach ($this->conexion->extraer_todos() as $fila) {
                $usuarios[] = Usuario::fromArray($fila);
            }
            return $usuarios;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener los usuarios: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Usuario $usuario): bool
    {
        try {
            if ($usuario->getId() === null || $usuario->getId() === 0) {
                return $this->insert($usuario);
            }
            return $this->update($usuario);

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al guardar el usuario: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function insert(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuarios 
            (nombre, apellidos, email, password, rol, confirmado, token, token_exp, created_at, updated_at)
            VALUES 
            (:nombre, :apellidos, :email, :password, :rol, :confirmado, :token, :token_exp, :created_at, :updated_at)";

        return $this->conexion->ejecutar($sql, [
            ':nombre' => $usuario->getNombre(),
            ':apellidos' => $usuario->getApellidos(),
            ':email' => $usuario->getEmail(),
            ':password' => $usuario->getPassword(),
            ':rol' => $usuario->getRol(),
            ':confirmado' => $usuario->getConfirmado(),
            ':token' => $usuario->getToken(),
            ':token_exp' => $usuario->getTokenExp(),
            ':created_at' => $usuario->getCreatedAt(),
            ':updated_at' => $usuario->getUpdatedAt(),
        ]);
    }

    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE usuarios SET
            nombre = :nombre,
            apellidos = :apellidos,
            email = :email,
            password = :password,
            rol = :rol,
            confirmado = :confirmado,
            token = :token,
            token_exp = :token_exp,
            updated_at = :updated_at
            WHERE id = :id";

        return $this->conexion->ejecutar($sql, [
            ':id' => $usuario->getId(),
            ':nombre' => $usuario->getNombre(),
            ':apellidos' => $usuario->getApellidos(),
            ':email' => $usuario->getEmail(),
            ':password' => $usuario->getPassword(),
            ':rol' => $usuario->getRol(),
            ':confirmado' => $usuario->getConfirmado(),
            ':token' => $usuario->getToken(),
            ':token_exp' => $usuario->getTokenExp(),
            ':updated_at' => $usuario->getUpdatedAt(),
        ]);
    }

    /**
     * Busca un usuario por su email
     * 
     * @param string $email Email a buscar
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public function findByEmail(string $email): ?Usuario
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $this->conexion->ejecutar($sql, [':email' => $email]);

            $resultado = $this->conexion->extraer();
            if (!$resultado) {
                return null;
            }

            return Usuario::fromArray($resultado);

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al buscar usuario por email: {$e->getMessage()}",
                previous: $e
            );
        }
    }
}