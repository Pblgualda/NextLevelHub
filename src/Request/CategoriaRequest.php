<?php

namespace NextLevelHub\Request;

class CategoriaRequest
{
    private array $data = [];
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(): bool
    {
        $this->errors = [];

        $nombre = $this->sanitize($this->data['nombre'] ?? '');
        if (empty($nombre) || mb_strlen($nombre) < 3) {
            $this->errors[] = 'El nombre de la categoría debe tener al menos 3 caracteres.';
        }
        $this->data['nombre'] = $nombre;

        $descripcion = $this->sanitize($this->data['descripcion'] ?? '');
        $this->data['descripcion'] = $descripcion;

        return empty($this->errors);
    }

    private function sanitize(string $value): string
    {
        $value = trim($value);
        $value = strip_tags($value);
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
