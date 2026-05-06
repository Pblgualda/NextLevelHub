<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Producto;
use RuntimeException;
use PDOException;

class ProductoRepository
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id";
            $this->conexion->ejecutar($sql);

            $productos = [];
            foreach($this->conexion->extraer_todos() as $fila){
                $productos[] = Producto::fromArray($fila);
            }
            return $productos;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener los productos: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findByCategoria(int $categoriaId): array
    {
        try {
            $sql = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id
                    WHERE p.categoria_id = :categoria_id";
            $this->conexion->ejecutar($sql, [':categoria_id' => ['valor' => $categoriaId]]);

            $productos = [];
            foreach ($this->conexion->extraer_todos() as $fila) {
                $productos[] = Producto::fromArray($fila);
            }
            return $productos;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener productos por categoría: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findById(int $id): ?Producto
    {
        try {
            $sql = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id
                    WHERE p.id = :id";
            $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);

            $fila = $this->conexion->extraer_registro();
            return $fila ? Producto::fromArray($fila) : null;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener el producto: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function decrementStock(int $id, int $cantidad): bool
    {
        try {
            $sql = 'UPDATE productos SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad_check';
            $exito = $this->conexion->ejecutar($sql, [
                ':id' => ['valor' => $id],
                ':cantidad' => ['valor' => $cantidad],
                ':cantidad_check' => ['valor' => $cantidad],
            ]);

            return $exito && $this->conexion->filasAfectadas() > 0;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al actualizar el stock del producto: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function hasLineasPedido(int $id): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM lineas_pedidos WHERE producto_id = :id";
            $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
            $result = $this->conexion->extraer_registro();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al verificar líneas de pedido: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM productos WHERE id = :id";
            return $this->conexion->ejecutar($sql, [':id' => ['valor' => $id]]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al eliminar el producto: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Producto $producto): bool
    {
        try{
            if ($producto->getId() === null || $producto->getId() === 0) {
                return $this->insert($producto);
            }
            return $this->update($producto);

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al guardar el producto: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function insert(Producto $producto): bool
    {
        $sql = "INSERT INTO productos 
            (categoria_id, nombre, descripcion, precio, precio_oferta, stock, activo, imagen, created_at, updated_at)
            VALUES 
            (:categoria_id, :nombre, :descripcion, :precio, :precio_oferta, :stock, :activo, :imagen, :created_at, :updated_at)";

        return $this->conexion->ejecutar($sql, [
            ':categoria_id'   => $producto->getCategoriaId(),
            ':nombre'         => $producto->getNombre(),
            ':descripcion'    => $producto->getDescripcion(),
            ':precio'         => $producto->getPrecio(),
            ':precio_oferta'  => $producto->getPrecioOferta(),
            ':stock'          => $producto->getStock(),
            ':activo'         => $producto->getActivo(),
            ':imagen'         => $producto->getImagen(),
            ':created_at'     => $producto->getCreatedAt(),
            ':updated_at'     => $producto->getUpdatedAt(),
        ]);
    }

    public function update(Producto $producto): bool
    {
        $sql = "UPDATE productos SET
            categoria_id = :categoria_id,
            nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            precio_oferta = :precio_oferta,
            stock = :stock,
            activo = :activo,
            imagen = :imagen,
            updated_at = :updated_at
            WHERE id = :id";

        return $this->conexion->ejecutar($sql, [
            ':id'             => $producto->getId(),
            ':categoria_id'   => $producto->getCategoriaId(),
            ':nombre'         => $producto->getNombre(),
            ':descripcion'    => $producto->getDescripcion(),
            ':precio'         => $producto->getPrecio(),
            ':precio_oferta'  => $producto->getPrecioOferta(),
            ':stock'          => $producto->getStock(),
            ':activo'         => $producto->getActivo(),
            ':imagen'         => $producto->getImagen(),
            ':updated_at'     => $producto->getUpdatedAt(),
        ]);
    }
}