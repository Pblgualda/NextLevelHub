<?php


namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Usuario;
use RuntimeException;
use PDOException;

class UsuarioRepository
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
            ':nombre' => ['valor' => $usuario->getNombre()],
            ':apellidos' => ['valor' => $usuario->getApellidos()],
            ':email' => ['valor' => $usuario->getEmail()],
            ':password' => ['valor' => $usuario->getPassword()],
            ':rol' => ['valor' => $usuario->getRol()],
            ':confirmado' => ['valor' => $usuario->isConfirmado()],
            ':token' => ['valor' => $usuario->getToken()],
            ':token_exp' => ['valor' => $usuario->getTokenExp()],
            ':created_at' => ['valor' => $usuario->getCreatedAt()],
            ':updated_at' => ['valor' => $usuario->getUpdatedAt()],
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
            created_at = :created_at,
            updated_at = :updated_at
            WHERE id = :id";

        return $this->conexion->ejecutar($sql, [
            ':nombre' => ['valor' => $usuario->getNombre()],
            ':apellidos' => ['valor' => $usuario->getApellidos()],
            ':email' => ['valor' => $usuario->getEmail()],
            ':password' => ['valor' => $usuario->getPassword()],
            ':rol' => ['valor' => $usuario->getRol()],
            ':confirmado' => ['valor' => $usuario->isConfirmado()],
            ':token' => ['valor' => $usuario->getToken()],
            ':token_exp' => ['valor' => $usuario->getTokenExp()],
            ':created_at' => ['valor' => $usuario->getCreatedAt()],
            ':updated_at' => ['valor' => $usuario->getUpdatedAt()],
            ':id' => ['valor' => $usuario->getId()],
        ]);
    }

    public function findById(int $id): ?Usuario
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
            $resultado = $this->conexion->extraer_registro();
            return $resultado ? Usuario::fromArray($resultado) : null;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al buscar usuario por id: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findByToken(string $token): ?Usuario
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE token = :token";
            $this->conexion->ejecutar($sql, [':token' => ['valor' => $token]]);
            $resultado = $this->conexion->extraer_registro();
            return $resultado ? Usuario::fromArray($resultado) : null;
        }catch(PDOException $e){
            throw new RuntimeException(
                "Error al buscar usuario por token: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function hasPedidos(int $usuarioId): bool
    {
        try {
            $sql = "SELECT 1 FROM pedidos WHERE usuario_id = :usuario_id LIMIT 1";
            $this->conexion->ejecutar($sql, [':usuario_id' => ['valor' => $usuarioId]]);
            $fila = $this->conexion->extraer_registro();
            return $fila !== false;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al verificar si el usuario tiene pedidos: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            return $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al eliminar el usuario: {$e->getMessage()}",
                previous: $e
            );
        }
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
             $this->conexion->ejecutar($sql, [
            ':email' => ['valor' => $email],
        ]);

            $resultado = $this->conexion->extraer_registro();
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