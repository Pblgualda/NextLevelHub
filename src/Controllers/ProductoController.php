<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\AdminMiddleware;
use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Producto;
use NextLevelHub\Request\ProductoRequest;
use NextLevelHub\Services\CategoriaService;
use NextLevelHub\Services\ProductoService;

class ProductoController
{
    private ProductoService $service;
    private CategoriaService $categoriaService;
    private Pages $pages;

    public function __construct()
    {
        $db = BaseDatos::getInstancia();
        $this->service = new ProductoService($db);
        $this->categoriaService = new CategoriaService($db);
        $this->pages = new Pages();
    }

    public function gestion(): void
    {
        AdminMiddleware::handle();
        $productos = $this->service->findAll();
        $this->pages->render('producto/showProductos', [
            'productos' => $productos,
            'titulo' => 'Listado de Productos',
        ]);
    }

    public function productosPorCategoria(int $categoriaId): void
    {
        $productos = $this->service->findByCategoria($categoriaId);
        $categoria = $this->categoriaService->findById($categoriaId);
        $titulo = $categoria ? 'Productos en ' . $categoria->getNombre() : 'Productos por categoría';

        $this->pages->render('producto/publicCategoria', [
            'productos' => $productos,
            'titulo' => $titulo,
            'categoria' => $categoria,
        ]);
    }

    public function form(?int $id = null): void
    {
        AdminMiddleware::handle();
        $errors = [];
        $data = [];
        $producto = null;

        if ($id !== null && $id > 0) {
            $producto = $this->service->findById($id);
            if (!$producto) {
                $_SESSION['errors'] = ['Producto no encontrado.'];
                header('Location: ' . BASE_URL . 'producto/gestion');
                return;
            }
            $data = [
                'categoria_id' => $producto->getCategoriaId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precio_oferta' => $producto->getPrecioOferta(),
                'stock' => $producto->getStock(),
                'activo' => $producto->getActivo(),
            ];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['data'] ?? [];
            $request = new ProductoRequest($data);

            if (!$request->validate()) {
                $errors = $request->getErrors();
                $data = $request->getData();
            } else {
                $data = $request->getData();
                $producto = $producto ?? new Producto();
                $producto = Producto::fromArray($data);

                if ($id !== null && $id > 0) {
                    $producto->setId($id);
                    $existing = $this->service->findById($id);
                    if ($existing) {
                        $producto->setCreatedAt($existing->getCreatedAt());
                    }
                } else {
                    $producto->setCreatedAt(date('Y-m-d H:i:s'));
                }

                $producto->setUpdatedAt(date('Y-m-d H:i:s'));

                $imagen = $this->service->processImageUpload($_FILES['imagen'] ?? []);
                if ($imagen !== null) {
                    $producto->setImagen($imagen);
                } elseif ($existing ?? false) {
                    $producto->setImagen($existing->getImagen());
                }

                $this->service->save($producto);
                $_SESSION['product_save'] = 'complete';
                header('Location: ' . BASE_URL . 'producto/gestion');
                return;
            }
        }

        $categorias = $this->categoriaService->findAll();

        $this->pages->render('producto/formProducto', [
            'errors' => $errors,
            'data' => $data,
            'categorias' => $categorias,
            'id' => $id,
            'producto' => $producto,
        ]);
    }

    public function eliminar(int $id): void
    {
        AdminMiddleware::handle();
        if ($id > 0) {
            try {
                $this->service->delete($id);
                $_SESSION['product_delete'] = 'complete';
            } catch (RuntimeException $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = ['ID de producto inválido.'];
        }
        header('Location: ' . BASE_URL . 'producto/gestion');
    }

    public function activar(int $id)
    {
        AdminMiddleware::handle();
        if ($id > 0) {
            try {
                $this->service->activar($id);
                $_SESSION['product_activate'] = 'complete';
            } catch (RuntimeException $e) {
                $_SESSION['errors'] = [$e->getMessage()];
            }
        } else {
            $_SESSION['errors'] = ['ID de producto inválido.'];
        }
        header('Location: ' . BASE_URL . 'producto/gestion');
    }
}
