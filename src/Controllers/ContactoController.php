<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Contacto;
use NextLevelHub\Request\ContactoRequest;
use NextLevelHub\Services\ContactoService;

class ContactoController
{
    private ContactoService $service;
    private Pages $pages;
    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new ContactoService($db);
        $this->pages = new Pages();
    }

    public function listar(): void{
        $contactos = $this->service->findAll();
        $this->pages->render('contacto/showContacts',['contactos'=> $contactos]);
    }

    public function nuevoContacto(): void{
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['data'])) {
            $this->pages->render('contacto/formContacto');
            return;
        }
        $data= ($_POST['data']);

        // $data = ContactoRequest::sanitize($_POST["data"]);

        // $errores = ContactoRequest::validate($data);

        if (!empty($errores)) {
            $contactoParcial = Contacto::fromArray($data);
            $this->pages->render('contacto/formContacto',[
                'contacto'=> $contactoParcial,
                'errores' => $errores]);
            return;
        }

        $contacto = Contacto::fromArray($data);
        $esNuevo = $contacto->getId() === null || $contacto->getId() === 0;
        $exito = $this->service->save($contacto);

        $resultado = match (true){
            !$exito => "No se ha podido crear el contacto",
            $esNuevo => "Contacto agregado con exito",
            default => "Contacto modificado con exito",
        };

        $this->pages->render('contacto/resultado',['resultado'=>$resultado]);

    }

}