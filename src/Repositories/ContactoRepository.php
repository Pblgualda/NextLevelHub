<?php

namespace Agenda\Repositories;

use Agenda\Core\BaseDatos;
use Agenda\Models\Contacto;
use RuntimeException;
use Throwable;

class ContactoRepository implements ContactoRepositoryInterface
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "Select * from contactos";
            $this->conexion->ejecutar($sql);

            $contactos =[];
            foreach($this->conexion->extraer_todos() as $fila){
                $contactos[]= Contacto::fromArray($fila);
            }
            return $contactos;
        } catch (\PDOException $e) {
            throw new RuntimeException("Error al obtener los contactos: {$e->getMessage()}",previous:$e);
        }
    }

    public function create(Contacto $contacto):bool{
        try{
            $sql ="Insert into contactos (nombre,apellidos,correo,direccion,telefono,fecha_nacimiento)
                Values(:nombre, :apellidos, :correo, :direccion, :telefono, :fecha_nacimiento)";
            $params = [
                ':nombre'=>['valor' => $contacto->getNombre()],
                ':apellidos'=>['valor' => $contacto->getApellido()],
                ':correo'=>['valor' => $contacto->getCorreo()],
                ':direccion'=>['valor' => $contacto->getDireccion()],
                ':telefono'=>['valor' => $contacto->getTelefono()],
                ':fecha_nacimiento'=>['valor' => $contacto->getFechaNacimiento()]
            ];
            $exito= $this->conexion->ejecutar($sql, $params);

            if($exito){
                $nuevoId = $this->conexion->ultimoIdInsertado();
                if($nuevoId>0){
                    $contacto->setId($nuevoId);
                }
            }
            return $exito;

        }catch(PDOException $e){
            throw new RuntimeException("ERROR AL CREAR UN NUEVO CONTACTO: {$e->getMessage()}", previous:$e);
        }
    }

    public function update(Contacto $contacto):bool{
        try{
            $sql ="Update contactos Set nombre=:nombre, apellidos=:apellidos, correo=:correo, direccion=:direccion, telefono=:telefono, fecha_nacimiento=:fecha_nacimiento Where id=:id";
            $params = [
                ':id'=>['valor' => $contacto->getId()],
                ':nombre'=>['valor' => $contacto->getNombre()],
                ':apellidos'=>['valor' => $contacto->getApellido()],
                ':correo'=>['valor' => $contacto->getCorreo()],
                ':direccion'=>['valor' => $contacto->getDireccion()],
                ':telefono'=>['valor' => $contacto->getTelefono()],
                ':fecha_nacimiento'=>['valor' => $contacto->getFechaNacimiento()]
            ];
            return $this->conexion->ejecutar($sql, $params);
        }catch(PDOException $e){
            throw new RuntimeException("ERROR AL ACTUALIZAR EL CONTACTO: {$e->getMessage()}", previous:$e);
        }
    }

    public function save (Contacto $contacto):bool{
        return ($contacto->getId()>0)?
            $this->update($contacto):
            $this->create($contacto);
    }




}