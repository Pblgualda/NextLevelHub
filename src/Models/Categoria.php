<?php

namespace NextLevelHub\Models;

    class Categoria
    {
        public function __construct(
            private int|null $id = null,
            private string   $nombre = '',
            private string   $descripcion = '',
            private string   $created_at = ''
        )
        {
        }


        public static function fromArray(array $data): self
        {
            $id = (isset($data['id']) && $data['id'] !== '') ? (int)$data['id'] : null;
            return new self(
                id: $id,
                nombre: $data['nombre'] ?? '',
                descripcion: $data['descripcion'] ?? '',
                created_at: $data['created_at'] ?? ''
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

        public function getNombre(): ?string
        {
            return $this->nombre;
        }

        public function setNombre(?string $nombre): void
        {
            $this->id = $nombre;
        }

        public function getDescripcion(): ?string
        {
            return $this->descripcion;
        }

        public function setDescripcion(?string $descripcion): void
        {
            $this->id = $descripcion;
        }

        public function getCreated(): ?string
        {
            return $this->created_at;
        }

        public function setCreated(?string $created_at): void
        {
            $this->id = $created_at;
        }

    }