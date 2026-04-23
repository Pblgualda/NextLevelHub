<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Pedido;
use NextLevelHub\Services\PedidoService;
// use NextLevelHub\Request\PedidoRequest; // opcional si lo tienes

class PedidoController
{
    private PedidoService $service;
    private Pages $pages;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new PedidoService($db);
        $this->pages = new Pages();
    }

    public function listar(): void{
        $pedidos = $this->service->findAll();
        $this->pages->render('pedido/showPedidos', [
            'pedidos' => $pedidos
        ]);
    }

    public function nuevoPedido(): void{
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['data'])) {
            $this->pages->render('pedido/formPedido');
            return;
        }

        $data = $_POST['data'];

        // Si tienes request validator, descomenta:
        // $data = PedidoRequest::sanitize($_POST["data"]);
        // $errores = PedidoRequest::validate($data);

        $errores = []; // temporal si no tienes validación

        if (!empty($errores)) {
            $pedidoParcial = Pedido::fromArray($data);
            $this->pages->render('pedido/formPedido', [
                'pedido' => $pedidoParcial,
                'errores'  => $errores
            ]);
            return;
        }

        $pedido = Pedido::fromArray($data);
        $esNuevo = $pedido->getId() === null || $pedido->getId() === 0;

        $exito = $this->service->save($pedido);

        $resultado = match (true) {
            !$exito  => "No se ha podido guardar el pedido",
            $esNuevo => "Pedido agregado con éxito",
            default  => "Pedido modificado con éxito",
        };

        $this->pages->render('pedido/resultado', [
            'resultado' => $resultado
        ]);
    }
}