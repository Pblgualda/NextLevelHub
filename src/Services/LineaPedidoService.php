<?php


namespace NextLevelHub\Services;

use NextLevelHub\Models\LineaPedido;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\LineaPedidoRepository;

class LineaPedidoService
{
    private LineaPedidoRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new LineaPedidoRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function save(LineaPedido $lineaPedido): bool
    {
        return $this->repository->save($lineaPedido);
    }

    public function update(LineaPedido $lineaPedido): bool
    {
        return $this->repository->update($lineaPedido);
    }
}