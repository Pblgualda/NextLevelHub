<?php
namespace NextLevelHub\Models;

class LineaPedido
{
    public function __construct(
        private ?int $id = null,
        private ?int $pedido_id = null,
        private int $producto_id = 0,
        private int $cantidad = 0,
        private float $precio = 0.0,
        private string $producto_nombre = ''
    ){
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null,
            pedido_id: isset($data['pedido_id']) && $data['pedido_id'] !== '' ? (int)$data['pedido_id'] : null,
            producto_id: isset($data['producto_id']) ? (int)$data['producto_id'] : 0,
            cantidad: isset($data['cantidad']) ? (int)$data['cantidad'] : (isset($data['unidades']) ? (int)$data['unidades'] : 0),
            precio: isset($data['precio']) ? (float)$data['precio'] : (isset($data['precio_unitario']) ? (float)$data['precio_unitario'] : 0.0),
            producto_nombre: $data['producto_nombre'] ?? $data['nombre'] ?? ''
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

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getProductoNombre(): string
    {
        return $this->producto_nombre;
    }

    public function setProductoNombre(string $producto_nombre): void
    {
        $this->producto_nombre = $producto_nombre;
    }
}
