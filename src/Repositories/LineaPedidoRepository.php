<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\LineaPedido;
use RuntimeException;
use PDOException;

class LineaPedidoRepository implements LineaPedidoRepositoryInterface
{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array
    {
        try{
            $sql = "SELECT * FROM lineas_pedido";
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

    public function create(LineaPedido $lineaPedido): bool
    {
        try{
            $sql = "INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio)
                    VALUES (:pedido_id, :producto_id, :cantidad, :precio)";

            $params = [
                ':pedido_id' => ['valor' => $lineaPedido->getPedidoId()],
                ':producto_id' => ['valor' => $lineaPedido->getProductoId()],
                ':cantidad' => ['valor' => $lineaPedido->getCantidad()],
                ':precio' => ['valor' => $lineaPedido->getPrecio()]
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
            $sql = "UPDATE lineas_pedido 
                    SET pedido_id = :pedido_id, producto_id = :producto_id, cantidad = :cantidad, precio = :precio
                    WHERE id = :id";

            $params = [
                ':id'          => ['valor' => $lineaPedido->getId()],
                ':pedido_id' => ['valor' => $lineaPedido->getPedidoId()],
                ':producto_id' => ['valor' => $lineaPedido->getProductoId()],
                ':cantidad' => ['valor' => $lineaPedido->getCantidad()],
                ':precio' => ['valor' => $lineaPedido->getPrecio()]
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