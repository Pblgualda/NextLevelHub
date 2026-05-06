<?php

namespace NextLevelHub\Controllers;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Core\Pages;
use NextLevelHub\Models\Producto;
use NextLevelHub\Services\ProductoService;

class CarritoController
{
    private ProductoService $productoService;
    private Pages $pages;

    public function __construct()
    {
        $db = BaseDatos::getInstancia();
        $this->productoService = new ProductoService($db);
        $this->pages = new Pages();
    }

    private function getCart(): array
    {
        return isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
    }

    private function setCart(array $cart): void
    {
        if (empty($cart)) {
            unset($_SESSION['carrito']);
            return;
        }

        $_SESSION['carrito'] = $cart;
    }

    private function findProduct(int $id): ?Producto
    {
        return $this->productoService->findById($id);
    }

    private function addToCart(int $productId, int $quantity, ?string &$message = null): bool
    {
        if ($productId <= 0 || $quantity <= 0) {
            $message = 'Cantidad o producto inválidos.';
            return false;
        }

        $producto = $this->findProduct($productId);
        if (!$producto) {
            $message = 'Producto no encontrado.';
            return false;
        }

        if ($producto->getStock() <= 0) {
            $message = 'No hay stock disponible de este producto.';
            return false;
        }

        $cart = $this->getCart();
        $current = $cart[$productId] ?? 0;
        $newQuantity = $current + $quantity;

        if ($newQuantity > $producto->getStock()) {
            $message = 'No se puede añadir esa cantidad. Stock insuficiente.';
            return false;
        }

        $cart[$productId] = $newQuantity;
        $this->setCart($cart);
        return true;
    }

    private function redirectBack(string $fallback = BASE_URL): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        header('Location: ' . ($referer !== '' ? $referer : $fallback));
        exit;
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectBack();
        }

        $productId = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        $quantity = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($this->addToCart($productId, $quantity, $message)) {
            $_SESSION['carrito_message'] = 'Producto añadido al carrito.';
        } else {
            $_SESSION['carrito_message'] = $message;
        }

        $this->redirectBack();
    }

    public function index(): void
    {
        $cart = $this->getCart();
        $items = [];
        $totalQuantity = 0;
        $totalCost = 0.0;
        $stockWarnings = [];
        $updatedCart = $cart;

        foreach ($cart as $productId => $quantity) {
            $product = $this->findProduct((int)$productId);
            if (!$product) {
                unset($updatedCart[$productId]);
                continue;
            }

            $stock = $product->getStock();
            if ($stock <= 0) {
                unset($updatedCart[$productId]);
                $stockWarnings[] = sprintf('El producto "%s" ya no está disponible y se ha eliminado del carrito.', $product->getNombre());
                continue;
            }

            if ($quantity > $stock) {
                $quantity = $stock;
                $updatedCart[$productId] = $stock;
                $stockWarnings[] = sprintf('La cantidad de "%s" se ha ajustado al stock disponible (%d unidades).', $product->getNombre(), $stock);
            }

            if ($quantity <= 0) {
                unset($updatedCart[$productId]);
                continue;
            }

            $price = $product->getPrecioOferta() !== null ? $product->getPrecioOferta() : $product->getPrecio();
            $subtotal = $price * $quantity;
            $totalQuantity += $quantity;
            $totalCost += $subtotal;

            $items[] = [
                'producto' => $product,
                'cantidad' => $quantity,
                'precio' => $price,
                'subtotal' => $subtotal,
            ];
        }

        if ($updatedCart !== $cart) {
            $this->setCart($updatedCart);
        }

        $message = $_SESSION['carrito_message'] ?? null;
        unset($_SESSION['carrito_message']);

        $this->pages->render('carrito/index', [
            'items' => $items,
            'totalQuantity' => $totalQuantity,
            'totalCost' => $totalCost,
            'stockWarnings' => $stockWarnings,
            'message' => $message,
        ]);
    }

    public function increment(int $productId): void
    {
        if ($this->addToCart($productId, 1, $message)) {
            $_SESSION['carrito_message'] = 'Cantidad incrementada correctamente.';
        } else {
            $_SESSION['carrito_message'] = $message;
        }

        header('Location: ' . BASE_URL . 'carrito');
        exit;
    }

    public function decrement(int $productId): void
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            $_SESSION['carrito_message'] = 'Producto no encontrado en el carrito.';
            header('Location: ' . BASE_URL . 'carrito');
            exit;
        }

        $cart[$productId]--;
        if ($cart[$productId] <= 0) {
            unset($cart[$productId]);
            $_SESSION['carrito_message'] = 'El producto se ha eliminado del carrito.';
        } else {
            $product = $this->findProduct($productId);
            if (!$product) {
                unset($cart[$productId]);
                $_SESSION['carrito_message'] = 'El producto ya no está disponible.';
            } elseif ($cart[$productId] > $product->getStock()) {
                $cart[$productId] = $product->getStock();
                $_SESSION['carrito_message'] = 'La cantidad se ajustó al stock disponible.';
            } else {
                $_SESSION['carrito_message'] = 'Cantidad reducida correctamente.';
            }
        }

        $this->setCart($cart);
        header('Location: ' . BASE_URL . 'carrito');
        exit;
    }

    public function remove(int $productId): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->setCart($cart);
            $_SESSION['carrito_message'] = 'Producto eliminado del carrito.';
        } else {
            $_SESSION['carrito_message'] = 'No se encontró el producto en el carrito.';
        }

        header('Location: ' . BASE_URL . 'carrito');
        exit;
    }

    public function empty(): void
    {
        unset($_SESSION['carrito']);
        $_SESSION['carrito_message'] = 'El carrito ha sido vaciado.';
        header('Location: ' . BASE_URL . 'carrito');
        exit;
    }

    public function confirm(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            $_SESSION['carrito_message'] = 'El carrito está vacío.';
            header('Location: ' . BASE_URL . 'carrito');
            exit;
        }

        if (!isset($_SESSION['identity'])) {
            $_SESSION['cart_redirect'] = BASE_URL . 'pedido/nuevo';
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        header('Location: ' . BASE_URL . 'pedido/nuevo');
        exit;
    }
}
