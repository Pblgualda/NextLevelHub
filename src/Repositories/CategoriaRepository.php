<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Categoria;
use RuntimeException;
use PDOException;

class CategoriaRepository
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM categorias";
            $this->conexion->ejecutar($sql);

            $categorias = [];
            foreach ($this->conexion->extraer_todos() as $fila) {
                $categorias[] = Categoria::fromArray($fila);
            }
            return $categorias;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener las categorías: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findAllR(): array
    {
        try {
            $sql = "SELECT DISTINCT categorias.* FROM categorias INNER JOIN productos ON categorias.id = productos.categoria_id";
            $this->conexion->ejecutar($sql);

            $categorias = [];
            foreach ($this->conexion->extraer_todos() as $fila) {
                $categorias[] = Categoria::fromArray($fila);
            }
            return $categorias;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener las categorías: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findById(int $id): ?Categoria
    {
        try {
            $sql = "SELECT * FROM categorias WHERE id = :id";
            $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
            $fila = $this->conexion->extraer_registro();
            return $fila ? Categoria::fromArray($fila) : null;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al buscar la categoría: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function existsByNombre(string $nombre, ?int $excludeId = null): bool
    {
        try {
            $sql = "SELECT id FROM categorias WHERE nombre = :nombre";
            $params = [':nombre' => ['valor' => $nombre]];
            if ($excludeId !== null && $excludeId > 0) {
                $sql .= " AND id != :excludeId";
                $params[':excludeId'] = ['valor' => $excludeId];
            }
            $this->conexion->ejecutar($sql, $params);
            $fila = $this->conexion->extraer_registro();
            return $fila !== false;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al verificar el nombre de la categoría: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function hasProducts(int $categoriaId): bool
    {
        try {
            $sql = "SELECT 1 FROM productos WHERE categoria_id = :categoria_id LIMIT 1";
            $this->conexion->ejecutar($sql, [':categoria_id' => ['valor' => $categoriaId]]);
            $fila = $this->conexion->extraer_registro();
            return $fila !== false;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al verificar si la categoría tiene productos: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM categorias WHERE id = :id";
            return $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al eliminar la categoría: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function create(Categoria $categoria): bool
    {
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion, created_at)
                    VALUES (:nombre, :descripcion, :created_at)";

            $params = [
                ':nombre' => ['valor' => $categoria->getNombre()],
                ':descripcion' => ['valor' => $categoria->getDescripcion()],
                ':created_at' => ['valor' => $categoria->getCreated()]
            ];

            $exito = $this->conexion->ejecutar($sql, $params);
            if ($exito) {
                $nuevoId = $this->conexion->ultimoIdInsertado();
                if ($nuevoId > 0) {
                    $categoria->setId($nuevoId);
                }
            }

            return $exito;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "ERROR AL CREAR UNA NUEVA CATEGORÍA: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function update(Categoria $categoria): bool
    {
        try {
            $sql = "UPDATE categorias
                    SET nombre = :nombre, descripcion = :descripcion, created_at = :created_at
                    WHERE id = :id";

            $params = [
                ':id' => ['valor' => $categoria->getId()],
                ':nombre' => ['valor' => $categoria->getNombre()],
                ':descripcion' => ['valor' => $categoria->getDescripcion()],
                ':created_at' => ['valor' => $categoria->getCreated()]
            ];

            return $this->conexion->ejecutar($sql, $params);

        } catch (PDOException $e) {
            throw new RuntimeException(
                "ERROR AL ACTUALIZAR LA CATEGORÍA: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Categoria $categoria): bool
    {
        return ($categoria->getId() > 0)
            ? $this->update($categoria)
            : $this->create($categoria);
    }
}
