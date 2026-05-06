<?php

namespace NextLevelHub\Request;

class LoginRequest
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

        $email = $this->sanitizeEmail($this->data['email'] ?? '');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'El email no tiene un formato válido.';
        }
        $this->data['email'] = $email;

        $password = $this->sanitize($this->data['password'] ?? '');
        if (empty($password)) {
            $this->errors[] = 'La contraseña es requerida.';
        }
        $this->data['password'] = $password;

        return empty($this->errors);
    }

    private function sanitize(string $value): string
    {
        $value = trim($value);
        $value = strip_tags($value);

        return $value;
    }

    private function sanitizeEmail(string $email): string
    {
        $email = trim(strtolower($email));
        return filter_var($email, FILTER_SANITIZE_EMAIL);
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
