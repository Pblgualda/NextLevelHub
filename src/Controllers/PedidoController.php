<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Services\PedidoService;

class PedidoController
{
    private PedidoService $service;
    private Pages $pages;

    public function __construct()
    {
        $db = BaseDatos::getInstancia();
        $this->service = new PedidoService($db);
        $this->pages = new Pages();
    }

    public function listar(): void
    {
        $pedidos = $this->service->findAll();
        $this->pages->render('pedido/showPedidos', [
            'pedidos' => $pedidos
        ]);
    }

    public function misPedidos(): void
    {
        $identity = $_SESSION['identity'] ?? null;
        if (!$identity) {
            $_SESSION['errors'] = ['Debes iniciar sesión para ver tus pedidos.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        $pedidos = $this->service->findOrdersByUsuarioId((int)$identity['id']);
        $this->pages->render('pedido/misPedidos', [
            'pedidos' => $pedidos,
        ]);
    }

    public function verPedido(int $id): void
    {
        $identity = $_SESSION['identity'] ?? null;
        if (!$identity) {
            $_SESSION['errors'] = ['Debes iniciar sesión para ver el pedido.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        $pedido = $this->service->findPedidoForUsuario($id, (int)$identity['id']);
        if (!$pedido) {
            $_SESSION['errors'] = ['Pedido no encontrado o no autorizado.'];
            header('Location: ' . BASE_URL . 'pedido/mis-pedidos');
            return;
        }

        $lineas = $this->service->findLineasByPedidoId($pedido->getId());
        $this->pages->render('pedido/detalle', [
            'pedido' => $pedido,
            'lineas' => $lineas,
        ]);
    }

    public function nuevoPedido(): void
    {
        $identity = $_SESSION['identity'] ?? null;
        if (!$identity) {
            $_SESSION['errors'] = ['Debes iniciar sesión para completar el pedido.'];
            header('Location: ' . BASE_URL . 'auth/login');
            return;
        }

        $cart = $_SESSION['carrito'] ?? [];
        if (empty($cart)) {
            $_SESSION['errors'] = ['El carrito está vacío.'];
            header('Location: ' . BASE_URL . 'carrito');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->pages->render('pedido/formPedido', [
                'data' => [],
                'errors' => [],
            ]);
            return;
        }

        $data = $_POST['data'] ?? [];
        $errors = [];

        $provincia = trim($data['provincia'] ?? '');
        $localidad = trim($data['localidad'] ?? '');
        $direccion = trim($data['direccion'] ?? '');

        if ($provincia === '') {
            $errors[] = 'La provincia es obligatoria.';
        }

        if ($localidad === '') {
            $errors[] = 'La localidad es obligatoria.';
        }

        if ($direccion === '') {
            $errors[] = 'La dirección es obligatoria.';
        }

        if (!empty($errors)) {
            $this->pages->render('pedido/formPedido', [
                'errors' => $errors,
                'data' => $data,
            ]);
            return;
        }

        try {
            $result = $this->service->processOrder(
                $cart,
                (int)$identity['id'],
                [
                    'provincia' => $provincia,
                    'localidad' => $localidad,
                    'direccion' => $direccion,
                ],
                $identity['email'] ?? ''
            );

            unset($_SESSION['carrito']);

            $this->pages->render('pedido/resultado', [
                'resultado' => 'Pedido confirmado correctamente.',
                'pedido' => $result['pedido'],
                'items' => $result['items'],
                'emailSent' => $result['emailSent'],
                'facturaPdf' => $result['facturaPdf'] ?? null,
                'facturaError' => $result['facturaError'] ?? null,
            ]);
        } catch (\Throwable $e) {
            $errors[] = $e->getMessage();
            $this->pages->render('pedido/formPedido', [
                'errors' => $errors,
                'data' => $data,
            ]);
        }
    }
}
