<?php

namespace NextLevelHub\Models;

class Usuario
{
    public function __construct(
        private int|null $id = null,
        private string $nombre = '',
        private string $apellidos = '',
        private string $email = '',
        private string $password = '',
        private string $rol = 'usuario',
        private bool $confirmado = false,
        private string|null $token = null,
        private string|null $token_exp = null,
        private string $created_at = '',
        private string $updated_at = ''
    )
    {
    }

    public static function fromArray(array $data): self
    {
        $id = (isset($data['id']) && $data['id'] !== '') ? (int)$data['id'] : null;

        return new self(
            id: $id,
            nombre: $data['nombre'] ?? '',
            apellidos: $data['apellidos'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            rol: $data['rol'] ?? 'usuario',
            confirmado: isset($data['confirmado']) ? (bool)$data['confirmado'] : false,
            token: $data['token'] ?? null,
            token_exp: $data['token_exp'] ?? null,
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

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function isConfirmado(): bool
    {
        return $this->confirmado;
    }

    public function setConfirmado(bool $confirmado): void
    {
        $this->confirmado = $confirmado;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getTokenExp(): ?string
    {
        return $this->token_exp;
    }

    public function setTokenExp(?string $token_exp): void
    {
        $this->token_exp = $token_exp;
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