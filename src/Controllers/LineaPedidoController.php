<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\LineaPedido;
use NextLevelHub\Services\LineaPedidoService;

class LineaPedidoController
{
    private LineaPedidoService $service;
    private Pages $pages;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->service = new LineaPedidoService();
        $this->pages = new Pages();
    }

    public function listar(): void{
        $lineas = $this->service->findAll();

        $this->pages->render('linea_pedido/index', [
            'lineas' => $lineas
        ]);
    }

    


}