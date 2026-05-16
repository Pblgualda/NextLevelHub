<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Categoria;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\CategoriaRepository;

class CategoriaService
{
    private CategoriaRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new CategoriaRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function findAllR(): array
    {
        return $this->repository->findAllR() ?? [];
    }

    public function findById(int $id): ?Categoria
    {
        return $this->repository->findById($id);
    }

    public function existsByNombre(string $nombre, ?int $excludeId = null): bool
    {
        return $this->repository->existsByNombre($nombre, $excludeId);
    }

    public function save(Categoria $categoria): bool
    {
        return $this->repository->save($categoria);
    }

    public function hasProducts(int $categoriaId): bool
    {
        return $this->repository->hasProducts($categoriaId);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
