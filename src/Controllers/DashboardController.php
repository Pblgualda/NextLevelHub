<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Services\CategoriaService;

class DashboardController
{
    private Pages $pages;
    private CategoriaService $categoriaService;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->pages = new Pages();
        $this->categoriaService = new CategoriaService($db);
    }

    public function index():void{
        $categorias = $this->categoriaService->findAllR();
        $this->pages->render("dashboard/index", [
            'categorias' => $categorias,
        ]);
    }
}