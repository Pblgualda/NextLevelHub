<?php


namespace NextLevelHub\Services;

use NextLevelHub\Models\Pedido;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Repositories\PedidoRepository;

class PedidoService
{
    private PedidoRepository $repository;

    public function __construct(BaseDatos $db)
    {
        $this->repository = new PedidoRepository($db);
    }

    public function findAll(): array
    {
        return $this->repository->findAll() ?? [];
    }

    public function save(Pedido $pedido): bool
    {
        return $this->repository->save($pedido);
    }

    public function update(Pedido $pedido): bool
    {
        return $this->repository->update($pedido);
    }
}