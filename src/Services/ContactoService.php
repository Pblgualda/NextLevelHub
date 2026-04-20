<?php

namespace Agenda\Services;

use Agenda\Models\Contacto;
use Agenda\Core\BaseDatos;
use Agenda\Repositories\ContactoRepository;

class ContactoService
{
    private ContactoRepository $repository;

    public function __construct (BaseDatos $db){
        $this->repository = new ContactoRepository($db);
    }


    public function findAll(): array{
        return $this->repository->findAll()?? [];
    }


    public function save(Contacto $contacto): bool{
        return $this->repository->save($contacto);
    }

    public function update(Contacto $contacto): bool{
        return $this->repository->update($contacto);
    }
}