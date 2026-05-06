<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Pedido;
use NextLevelHub\Models\Usuario;
use NextLevelHub\Repositories\UsuarioRepository;
use NextLevelHub\Core\BaseDatos;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private BaseDatos $db;
    private UsuarioRepository $usuarioRepository;

    public function __construct(BaseDatos $db)
    {
        $this->db = $db;
        $this->usuarioRepository = new UsuarioRepository($db);
    }

    public function sendOrderConfirmation(Pedido $pedido, array $carrito, string $email): bool
    {
        try {
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'] ?? 'tls';
            $mail->Port = (int)($_ENV['SMTP_PORT'] ?? 587);

            // Configuración del remitente
            $mail->setFrom($_ENV['SMTP_USER'], 'NextLevelHub');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de Pedido - NextLevelHub';

            // Obtener datos del usuario
            $usuario = $this->usuarioRepository->findById($pedido->getUsuarioId());
            $nombreCliente = $usuario ? $usuario->getNombre() . ' ' . $usuario->getApellidos() : 'Cliente';

            // Generar el cuerpo del correo
            $mail->Body = $this->generateOrderEmailBody($pedido, $carrito, $nombreCliente);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error or handle it
            error_log("Error sending email: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function generateOrderEmailBody(Pedido $pedido, array $carrito, string $nombreCliente): string
    {
        $fechaPedido = $pedido->getFechaPedido() ?: date('Y-m-d H:i:s');
        $numeroPedido = $pedido->getId();

        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmacion de Pedido</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .order-details { margin: 20px 0; }
                .product-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .product-table th, .product-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .product-table th { background-color: #f2f2f2; }
                .total { font-weight: bold; font-size: 18px; margin: 20px 0; }
                .footer { background-color: #007bff; color: white; padding: 20px; text-align: center; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Confirmacion de Pedido</h1>
                    <p>¡Gracias por tu compra en NextLevelHub!</p>
                </div>
                <div class="content">
                    <p>Hola ' . htmlspecialchars($nombreCliente) . ',</p>
                    <p>Tu pedido ha sido confirmado exitosamente. A continuación, encontrarás los detalles de tu compra:</p>

                    <div class="order-details">
                        <p><strong>Número de Pedido:</strong> ' . htmlspecialchars($numeroPedido) . '</p>
                        <p><strong>Fecha del Pedido:</strong> ' . htmlspecialchars($fechaPedido) . '</p>
                    </div>

                    <h3>Productos</h3>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($carrito as $item) {
            $producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $precioUnitario = $producto->getPrecioOferta() ?? $producto->getPrecio();
            $subtotal = $item['subtotal'];

            $html .= '
                            <tr>
                                <td>' . htmlspecialchars($producto->getNombre()) . '</td>
                                <td>' . htmlspecialchars($cantidad) . '</td>
                                <td>' . number_format($precioUnitario, 2) . ' €</td>
                                <td>' . number_format($subtotal, 2) . ' €</td>
                            </tr>';
        }

        $html .= '
                        </tbody>
                    </table>

                    <div class="total">
                        <p><strong>Subtotal:</strong> ' . number_format($pedido->getSubtotal(), 2) . ' €</p>
                        <p><strong>Impuestos (IVA 21%):</strong> ' . number_format($pedido->getImpuestos(), 2) . ' €</p>
                        <p><strong>Total:</strong> ' . number_format($pedido->getCosteTotal(), 2) . ' €</p>
                    </div>

                    <h3>Dirección de Envío</h3>
                    <p>
                        ' . htmlspecialchars($pedido->getDireccion()) . '<br>
                        ' . htmlspecialchars($pedido->getLocalidad()) . ', ' . htmlspecialchars($pedido->getProvincia()) . '
                    </p>

                    <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>
                    <p>¡Gracias por elegir NextLevelHub!</p>
                </div>
                <div class="footer">
                    <p>&copy; 2026 NextLevelHub. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}