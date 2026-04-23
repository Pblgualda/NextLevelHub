<?php

namespace NextLevelHub\Models;

class LineaPedido
{
    public function __construct(
        private ?int $id = null,
        private ?int $pedido_id = null,
        private int $producto_id = 0,
        private int $unidades = 0,
        private float $precio_unitario = 0.0,
        private ?float $subtotal_linea = 0.0,
    ){}

    public static function fromArray(array $data): self{
        return new self(
            id: isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null,
            pedido_id: isset($data['pedido_id']) && $data['pedido_id'] !== '' ? (int)$data['pedido_id'] : null,
            producto_id: isset($data['producto_id']) ? (int)$data['producto_id'] : 0,
            unidades: isset($data['unidades']) ? (int)$data['unidades'] : 0,
            precio_unitario: isset($data['precio_unitario']) ? (float)$data['precio_unitario'] : 0,
            subtotal_linea: isset($data['subtotal_linea']) ? (float)$data['subtotal_linea'] : null
        );
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPedidoId(): ?int
    {
        return $this->pedido_id;
    }

    public function setPedidoId(?int $pedido_id): void
    {
        $this->pedido_id = $pedido_id;
    }

    public function getProductoId(): int
    {
        return $this->producto_id;
    }

    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    public function getUnidades(): int
    {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): void
    {
        $this->unidades = $unidades;
    }

    public function getPrecioUnitario(): float
    {
        return $this->precio_unitario;
    }

    public function setPrecioUnitario(float $precio_unitario): void
    {
        $this->precio_unitario = $precio_unitario;
    }

    public function getSubtotalLinea(): ?float
    {
        return $this->subtotal_linea;
    }

    public function setSubtotalLinea(?float $subtotal_linea): void
    {
        $this->subtotal_linea = $subtotal_linea;
    }


}