<?php

namespace Agenda\Models;

class Contacto
{
        public function __construct(
            private int|null $id = null,
            private string $nombre = '',
            private string $apellido = '',
            private string $correo = '',
            private string $telefono = '',
            private string $direccion = '',
            private string $fecha_nacimiento = '',
        ){}

    public static function fromArray(array $data): self{
        $id =(isset($data['id']) && $data['id'] !==  '') ? (int)$data['id'] : null;
        return new self(
            id: $id,
            nombre: $data['nombre'] ?? '',
            apellido: $data['apellido'] ?? '',
            correo: $data['correo'] ?? '',
            telefono: $data['telefono'] ?? '',
            direccion: $data['direccion'] ?? '',
            fecha_nacimiento: $data['fecha_nacimiento'] ?? ''
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

    public function getFechaNacimiento(): string
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(string $fecha_nacimiento): void
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }


}