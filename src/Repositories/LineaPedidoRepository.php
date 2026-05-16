<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\LineaPedido;
use RuntimeException;
use PDOException;

class LineaPedidoRepository
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "SELECT id, pedido_id, producto_id, unidades AS cantidad, precio_unitario AS precio, subtotal_linea FROM lineas_pedidos";
            $this->conexion->ejecutar($sql);

            $lineasPedido = [];
            foreach($this->conexion->extraer_todos() as $fila){
                $lineasPedido[] = LineaPedido::fromArray($fila);
            }
            return $lineasPedido;

        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener las líneas de pedido: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function findByPedidoId(int $pedidoId): array
    {
        try {
            $sql = "SELECT lp.*, p.nombre AS producto_nombre FROM lineas_pedidos lp
                    LEFT JOIN productos p ON lp.producto_id = p.id
                    WHERE lp.pedido_id = :pedido_id";
            $this->conexion->ejecutar($sql, [':pedido_id' => ['valor' => $pedidoId]]);

            $lineasPedido = [];
            foreach ($this->conexion->extraer_todos() as $fila) {
                $lineasPedido[] = LineaPedido::fromArray($fila);
            }
            return $lineasPedido;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al obtener las líneas del pedido: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function deleteByPedidoId(int $pedidoId): bool
    {
        try {
            $sql = "DELETE FROM lineas_pedidos WHERE pedido_id = :pedido_id";
            return $this->conexion->ejecutar($sql, [':pedido_id' => ['valor' => $pedidoId]]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error al eliminar las líneas de pedido: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function create(LineaPedido $lineaPedido): bool
    {
        try{
            $sql = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades, precio_unitario, subtotal_linea)
                    VALUES (:pedido_id, :producto_id, :unidades, :precio_unitario, :subtotal_linea)";

            $params = [
                ':pedido_id' => ['valor' => $lineaPedido->getPedidoId()],
                ':producto_id' => ['valor' => $lineaPedido->getProductoId()],
                ':unidades' => ['valor' => $lineaPedido->getCantidad()],
                ':precio_unitario' => ['valor' => $lineaPedido->getPrecio()],
                ':subtotal_linea' => ['valor' => $lineaPedido->getCantidad() * $lineaPedido->getPrecio()]
            ];

            $exito = $this->conexion->ejecutar($sql, $params);

            if($exito){
                $nuevoId = $this->conexion->ultimoIdInsertado();
                if($nuevoId > 0){
                    $lineaPedido->setId($nuevoId);
                }
            }

            return $exito;

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL CREAR UNA NUEVA LÍNEA DE PEDIDO: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function update(LineaPedido $lineaPedido): bool
    {
        try{
            $sql = "UPDATE lineas_pedidos 
                    SET pedido_id = :pedido_id, producto_id = :producto_id, unidades = :unidades, precio_unitario = :precio_unitario, subtotal_linea = :subtotal_linea
                    WHERE id = :id";

            $params = [
                ':id'          => ['valor' => $lineaPedido->getId()],
                ':pedido_id' => ['valor' => $lineaPedido->getPedidoId()],
                ':producto_id' => ['valor' => $lineaPedido->getProductoId()],
                ':unidades' => ['valor' => $lineaPedido->getCantidad()],
                ':precio_unitario' => ['valor' => $lineaPedido->getPrecio()],
                ':subtotal_linea' => ['valor' => $lineaPedido->getCantidad() * $lineaPedido->getPrecio()]
            ];

            return $this->conexion->ejecutar($sql, $params);

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL ACTUALIZAR LA LÍNEA DE PEDIDO: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(LineaPedido $lineaPedido): bool
    {
        return ($lineaPedido->getId() > 0)
            ? $this->update($lineaPedido)
            : $this->create($lineaPedido);
    }
}