<?php
namespace NextLevelHub\Services;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\LineaPedido;
use NextLevelHub\Models\Pedido;
use NextLevelHub\Repositories\LineaPedidoRepository;
use NextLevelHub\Repositories\PedidoRepository;
use NextLevelHub\Repositories\ProductoRepository;
use RuntimeException;

class PedidoService
{
    private BaseDatos $db;
    private PedidoRepository $pedidoRepository;
    private LineaPedidoRepository $lineaRepository;
    private ProductoRepository $productoRepository;
    private EmailService $emailService;
    private const IVA_RATE = 0.21;

    public function __construct(BaseDatos $db)
    {
        $this->db = $db;
        $this->pedidoRepository = new PedidoRepository($db);
        $this->lineaRepository = new LineaPedidoRepository($db);
        $this->productoRepository = new ProductoRepository($db);
        $this->emailService = new EmailService($db);
    }

    public function findAll(): array
    {
        return $this->pedidoRepository->findAll() ?? [];
    }

    public function findOrdersByUsuarioId(int $usuarioId): array
    {
        return $this->pedidoRepository->findByUsuarioId($usuarioId) ?? [];
    }

    public function findPedidoForUsuario(int $pedidoId, int $usuarioId): ?Pedido
    {
        return $this->pedidoRepository->findByIdAndUsuarioId($pedidoId, $usuarioId);
    }

    public function findLineasByPedidoId(int $pedidoId): array
    {
        return $this->lineaRepository->findByPedidoId($pedidoId) ?? [];
    }

    public function save(Pedido $pedido): bool
    {
        return $this->pedidoRepository->save($pedido);
    }

    public function update(Pedido $pedido): bool
    {
        return $this->pedidoRepository->update($pedido);
    }

    public function processOrder(array $cart, int $usuarioId, array $address, string $email): array
    {
        if (empty($cart)) {
            throw new RuntimeException('No se puede procesar el pedido: el carrito está vacío.');
        }

        $items = [];
        $subtotal = 0.0;

        foreach ($cart as $productId => $quantity) {
            $productId = (int)$productId;
            $quantity = (int)$quantity;

            if ($productId <= 0 || $quantity <= 0) {
                throw new RuntimeException('Carrito inválido: producto o cantidad incorrecta.');
            }

            $producto = $this->productoRepository->findById($productId);
            if (!$producto) {
                throw new RuntimeException("El producto con ID {$productId} no existe.");
            }

            if ($producto->getStock() < $quantity) {
                throw new RuntimeException("El producto '{$producto->getNombre()}' no tiene stock suficiente.");
            }

            $price = $producto->getPrecioOferta() !== null ? $producto->getPrecioOferta() : $producto->getPrecio();
            $lineTotal = round($price * $quantity, 2);
            $subtotal += $lineTotal;

            $items[] = [
                'producto' => $producto,
                'cantidad' => $quantity,
                'precio' => $price,
                'subtotal' => $lineTotal,
            ];
        }

        $subtotal = round($subtotal, 2);
        $impuestos = round($subtotal * self::IVA_RATE, 2);
        $costeTotal = round($subtotal + $impuestos, 2);

        $pedido = new Pedido(
            null,
            $usuarioId,
            $address['provincia'] ?? '',
            $address['localidad'] ?? '',
            $address['direccion'] ?? '',
            $subtotal,
            $impuestos,
            $costeTotal,
            'confirmado',
            date('Y-m-d H:i:s')
        );

        $this->db->iniciarTransaccion();

        try {
            if (!$this->pedidoRepository->create($pedido)) {
                throw new RuntimeException('No se pudo crear el pedido en la base de datos.');
            }

            foreach ($items as $item) {
                $lineItem = new LineaPedido(
                    null,
                    $pedido->getId(),
                    $item['producto']->getId(),
                    $item['cantidad'],
                    $item['precio']
                );

                if (!$this->lineaRepository->create($lineItem)) {
                    throw new RuntimeException('No se pudo guardar la línea de pedido para el producto ' . $item['producto']->getNombre());
                }

                if (!$this->productoRepository->decrementStock($item['producto']->getId(), $item['cantidad'])) {
                    throw new RuntimeException('No se pudo actualizar el stock para el producto ' . $item['producto']->getNombre());
                }
            }

            $this->db->confirmar();
            $emailSent = $this->emailService->sendOrderConfirmation($pedido, $items, $email);

            return [
                'pedido' => $pedido,
                'items' => $items,
                'emailSent' => $emailSent,
            ];
        } catch (\Throwable $e) {
            $this->db->revertir();
            throw $e;
        }
    }
}
