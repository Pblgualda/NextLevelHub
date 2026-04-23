<?php

namespace NextLevelHub\Models;

class Producto
{
    public function __construct(
        private int|null    $id = null,
        private int         $categoria_id = 0,
        private string      $nombre = '',
        private string      $descripcion = '',
        private float       $precio = 0.0,
        private float|null  $precio_oferta = null,
        private int         $stock = 0,
        private int         $activo = 1,
        private string|null $imagen = null,
        private string      $created_at = '',
        private string      $updated_at = ''
    )
    {}

    public static function fromArray(array $data): self
    {
        $id = (isset($data['id']) && $data['id'] !== '') ? (int)$data['id'] : null;

        return new self(
            id: $id,
            categoria_id: isset($data['categoria_id']) ? (int)$data['categoria_id'] : 0,
            nombre: $data['nombre'] ?? '',
            descripcion: $data['descripcion'] ?? '',
            precio: isset($data['precio']) ? (float)$data['precio'] : 0.0,
            precio_oferta: (isset($data['precio_oferta']) && $data['precio_oferta'] !== '')
                ? (float)$data['precio_oferta']
                : null,
            stock: isset($data['stock']) ? (int)$data['stock'] : 0,
            activo: isset($data['activo']) ? (int)$data['activo'] : 1,
            imagen: $data['imagen'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? ''
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

    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    public function setCategoriaId(int $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getPrecioOferta(): ?float
    {
        return $this->precio_oferta;
    }

    public function setPrecioOferta(?float $precio_oferta): void
    {
        $this->precio_oferta = $precio_oferta;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getActivo(): int
    {
        return $this->activo;
    }

    public function setActivo(int $activo): void
    {
        $this->activo = $activo;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}