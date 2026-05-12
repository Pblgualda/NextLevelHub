<?php

namespace NextLevelHub\Request;

class ProductoRequest
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
            $this->errors[] = 'El nombre del producto debe tener al menos 3 caracteres.';
        }
        $this->data['nombre'] = $nombre;

        $descripcion = $this->sanitize($this->data['descripcion'] ?? '');
        $this->data['descripcion'] = $descripcion;

        $categoriaId = isset($this->data['categoria_id']) ? (int)$this->data['categoria_id'] : 0;
        if ($categoriaId <= 0) {
            $this->errors[] = 'Debes seleccionar una categoría válida.';
        }
        $this->data['categoria_id'] = $categoriaId;

        $precio = isset($this->data['precio']) ? filter_var($this->data['precio'], FILTER_VALIDATE_FLOAT) : false;
        if ($precio === false || $precio <= 0) {
            $this->errors[] = 'El precio debe ser un número válido mayor que cero.';
        }
        $precio = isset($this->data['precio']) ? filter_var($this->data['precio'], FILTER_VALIDATE_FLOAT) : false;
        if ($precio >= 500) {
            $this->errors[] = 'El precio no puede ser mayor de 500.';
        }
        $this->data['precio'] = $precio !== false ? (float)$precio : 0.0;

        $stock = isset($this->data['stock']) ? filter_var($this->data['stock'], FILTER_VALIDATE_INT) : false;
        if ($stock === false || $stock < 0) {
            $this->errors[] = 'El stock debe ser un número entero igual o mayor que cero.';
        }
        $this->data['stock'] = $stock !== false ? (int)$stock : 0;

        $precioOferta = $this->data['precio_oferta'] ?? null;
        if ($precioOferta !== null && $precioOferta !== '') {
            $precioOferta = filter_var($precioOferta, FILTER_VALIDATE_FLOAT);
            if ($precioOferta === false || $precioOferta < 0) {
                $this->errors[] = 'El precio de oferta debe ser un número válido igual o mayor que cero.';
            }
            $this->data['precio_oferta'] = $precioOferta !== false ? (float)$precioOferta : null;
        } else {
            $this->data['precio_oferta'] = null;
        }

        $activo = isset($this->data['activo']) ? (int)$this->data['activo'] : 1;
        $this->data['activo'] = $activo === 1 ? 1 : 0;

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
