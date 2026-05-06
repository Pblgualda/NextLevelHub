<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Producto;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\ProductoRepository;
use RuntimeException;

class ProductoService
{
    private ProductoRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new ProductoRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function findByCategoria(int $categoriaId): array
    {
        return $this->repository->findByCategoria($categoriaId) ?? [];
    }

    public function findById(int $id): ?Producto
    {
        return $this->repository->findById($id);
    }

    public function save(Producto $producto): bool
    {
        return $this->repository->save($producto);
    }

    public function update(Producto $producto): bool
    {
        return $this->repository->update($producto);
    }

    public function delete(int $id): bool
    {
        if ($this->repository->hasLineasPedido($id)) {
            throw new RuntimeException("No se puede eliminar el producto porque está asociado a pedidos existentes.");
        }
        return $this->repository->delete($id);
    }

    public function processImageUpload(array $imageFile): ?string
    {
        if (empty($imageFile['tmp_name']) || !is_uploaded_file($imageFile['tmp_name'])) {
            return null;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $info = pathinfo($imageFile['name']);
        $extension = strtolower($info['extension'] ?? '');

        if (!in_array($extension, $allowed, true)) {
            throw new RuntimeException('El archivo de imagen debe ser JPG, PNG o GIF.');
        }

        $baseName = pathinfo($info['filename'], PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $baseName);
        $name = $safeName . '_' . time() . '.' . $extension;

        $root = dirname(__DIR__, 2);
        $uploadDir = $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            throw new RuntimeException('No se pudo crear la carpeta de imágenes.');
        }

        $destination = $uploadDir . DIRECTORY_SEPARATOR . $name;
        if (!move_uploaded_file($imageFile['tmp_name'], $destination)) {
            throw new RuntimeException('No se pudo mover el archivo de imagen.');
        }

        return $name;
    }
}
