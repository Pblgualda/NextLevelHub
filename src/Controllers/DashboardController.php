<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Services\CategoriaService;
use NextLevelHub\Services\ProductoService;

class DashboardController
{
    private Pages $pages;
    private CategoriaService $categoriaService;
    private ProductoService $productoService;

    public function __construct(){
        $db = BaseDatos::getInstancia();
        $this->pages = new Pages();
        $this->categoriaService = new CategoriaService($db);
        $this->productoService = new ProductoService($db);
    }

    public function index():void{
        $categorias = $this->categoriaService->findAllR();
        $productos = $this->productoService->findFeatured(6);
        $this->pages->render("dashboard/index", [
            'categorias' => $categorias,
            'productos' => $productos,
        ]);
    }
}
