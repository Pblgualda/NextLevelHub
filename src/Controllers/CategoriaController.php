<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\AdminMiddleware;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Categoria;
use NextLevelHub\Request\CategoriaRequest;
use NextLevelHub\Services\CategoriaService;

class CategoriaController
{
    private CategoriaService $service;
    private Pages $pages;

    public function __construct()
    {
        AdminMiddleware::handle();
        $db = BaseDatos::getInstancia();
        $this->service = new CategoriaService($db);
        $this->pages = new Pages();
    }

    public function listar(): void
    {
        $categorias = $this->service->findAll();
        $this->pages->render('categoria/showCategorias', [
            'categorias' => $categorias
        ]);
    }

    public function form(?int $id = null): void
    {
        $categoria = null;
        if ($id !== null && $id > 0) {
            $categoria = $this->service->findById($id);
            if (!$categoria) {
                $_SESSION['errors'] = ['Categoría no encontrada.'];
                header('Location: ' . BASE_URL . 'categoria/listar');
                exit;
            }
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['data'] ?? [];
            $request = new CategoriaRequest($data);

            if (!$request->validate()) {
                $errors = $request->getErrors();
            } else {
                $data = $request->getData();
                $nombre = $data['nombre'];
                $existe = $this->service->existsByNombre($nombre, $id);

                if ($existe) {
                    $errors[] = 'Ya existe una categoría con ese nombre.';
                } else {
                    $categoria = Categoria::fromArray($data);
                    if ($id !== null && $id > 0) {
                        $categoria->setId($id);
                    }

                    $fecha = date('Y-m-d H:i:s');
                    if ($categoria->getCreated() === '') {
                        $categoria->setCreated($fecha);
                    }
                    $categoria->setCreated($fecha);

                    $this->service->save($categoria);
                    $_SESSION['categoria_save'] = 'complete';
                    header('Location: ' . BASE_URL . 'categoria/listar');
                    exit;
                }
            }
        }

        if (!$categoria) {
            $categoria = new Categoria();
        }

        $this->pages->render('categoria/formCategoria', [
            'categoria' => $categoria,
            'errors' => $errors,
            'isEdit' => $id !== null
        ]);
    }

    public function eliminar(int $id): void
    {
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID de categoría inválido.'];
            header('Location: ' . BASE_URL . 'categoria/listar');
            exit;
        }

        $this->service->delete($id);
        $_SESSION['categoria_delete'] = 'complete';
        header('Location: ' . BASE_URL . 'categoria/listar');
        exit;
    }
}
