<?php

namespace NextLevelHub\Models;

use NextLevelHub\Core\BaseDatos;

class Pedido
{
    public function __construct(
        private ?int $id = null,
        private int $usuario_id,
        private string $provincia,
        private string $localidad,
        private string $direccion,
        private float $subtotal = 0.0,
        private float $impuestos = 0.0,
        private float $coste_total = 0.0,
        private string $estado = 'pendiente',
        private string $fecha_pedido = ''
    ){}

    public static function fromArray(array $data): self{
        return new self(
            id: isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null,
            usuario_id: isset($data['usuario_id']) ? (int)$data['usuario_id'] : 0,
            provincia: $data['provincia'] ?? '',
            localidad: $data['localidad'] ?? '',
            direccion: $data['direccion'] ?? '',
            subtotal: isset($data['subtotal']) ? (float)$data['subtotal'] : 0,
            impuestos: isset($data['impuestos']) ? (float)$data['impuestos'] : 0,
            coste_total: isset($data['coste_total']) ? (float)$data['coste_total'] : 0,
            estado: $data['estado'] ?? 'pendiente',
            fecha_pedido: $data['fecha_pedido'] ?? ''
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

    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getProvincia(): string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): void
    {
        $this->provincia = $provincia;
    }

    public function getLocalidad(): string
    {
        return $this->localidad;
    }

    public function setLocalidad(string $localidad): void
    {
        $this->localidad = $localidad;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function getImpuesto(): float
    {
        return $this->impuesto;
    }

    public function setImpuesto(float $impuesto): void
    {
        $this->impuesto = $impuesto;
    }

    public function getCosteTotal(): float
    {
        return $this->coste_total;
    }

    public function setCosteTotal(float $coste_total): void
    {
        $this->coste_total = $coste_total;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getFechaPedido(): string
    {
        return $this->fecha_pedido;
    }

    public function setFechaPedido(string $fecha_pedido): void
    {
        $this->fecha_pedido = $fecha_pedido;
    }

}