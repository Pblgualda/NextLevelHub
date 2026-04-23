<?php

namespace NextLevelHub\Request;

class UserRequest
{
    private array $data = [];
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Sanitiza y valida los datos del formulario de registro
     * 
     * @return bool true si la validación es exitosa, false en caso contrario
     */
    public function validate(): bool
    {
        $this->errors = [];

        // Validar nombre
        $nombre = $this->sanitize($this->data['nombre'] ?? '');
        if (empty($nombre) || strlen($nombre) < 3) {
            $this->errors[] = 'El nombre debe tener al menos 3 caracteres.';
        }
        $this->data['nombre'] = $nombre;

        // Validar apellidos
        $apellidos = $this->sanitize($this->data['apellidos'] ?? '');
        if (empty($apellidos) || strlen($apellidos) < 3) {
            $this->errors[] = 'Los apellidos deben tener al menos 3 caracteres.';
        }
        $this->data['apellidos'] = $apellidos;

        // Validar email
        $email = $this->sanitizeEmail($this->data['email'] ?? '');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'El email no tiene un formato válido.';
        }
        $this->data['email'] = $email;

        // Validar contraseña
        $password = $this->data['password'] ?? '';
        if (empty($password) || strlen($password) < 8) {
            $this->errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        
        // Validar confirmación de contraseña
        $passwordConfirm = $this->data['password_confirm'] ?? '';
        if ($password !== $passwordConfirm) {
            $this->errors[] = 'Las contraseñas no coinciden.';
        }
        $this->data['password'] = $password;

        return empty($this->errors);
    }

    /**
     * Sanitiza una cadena de texto eliminando espacios, etiquetas HTML y caracteres peligrosos
     * 
     * @param string $value Valor a sanitizar
     * @return string Valor sanitizado
     */
    private function sanitize(string $value): string
    {
        // Eliminar espacios en blanco al inicio y final
        $value = trim($value);
        
        // Convertir caracteres especiales HTML a entidades
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        // Eliminar cualquier etiqueta HTML
        $value = strip_tags($value);

        return $value;
    }

    /**
     * Sanitiza un email
     * 
     * @param string $email Email a sanitizar
     * @return string Email sanitizado
     */
    private function sanitizeEmail(string $email): string
    {
        $email = trim($email);
        $email = strtolower($email);
        // filter_var con FILTER_SANITIZE_EMAIL elimina caracteres inválidos
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    /**
     * Retorna los datos sanitizados
     * 
     * @return array Datos sanitizados
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Retorna los errores de validación
     * 
     * @return array Array de errores
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Obtiene un valor específico sanitizado
     * 
     * @param string $key Clave del dato
     * @return string Valor sanitizado o cadena vacía
     */
    public function get(string $key): string
    {
        return $this->data[$key] ?? '';
    }
}
