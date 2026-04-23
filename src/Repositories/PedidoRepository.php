<?php

namespace NextLevelHub\Repositories;

use NextLevelHub\Core\BaseDatos;
use NextLevelHub\Models\Pedido;
use PDOException;
use RuntimeException;

class PedidoRepository{
    public function __construct(
        private readonly BaseDatos $conexion
    ){}

    public function findAll(): array{
        try{
            $sql = "Select * from pedidos ORDER BY fecha_pedido DESC, id DESC";
            $this->conexion->ejecutar($sql);
            $pedidos = [];

            foreach($this->conexion->extraer_todos() as $fila){
                $pedidos[] = Pedido::fromArray($fila);
            }

            return $pedidos;
        }catch(PDOException $e){
            throw new RuntimeException(
                "Error al obtener los pedidos: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    //findByID no implementado porque no se necesita en este proyecto, pero se podría implementar

    public function create(Pedido $pedido): bool
    {
        try{
            $sql = "INSERT INTO pedidos
                    (usuario_id, provincia, localidad, direccion, subtotal, impuestos, coste_total, estado, fecha_pedido)
                    VALUES
                    (:usuario_id, :provincia, :localidad, :direccion, :subtotal, :impuestos, :coste_total, :estado, :fecha_pedido)";

            $params = [
                ':usuario_id'   => ['valor' => $pedido->getUsuarioId()],
                ':provincia'    => ['valor' => $pedido->getProvincia()],
                ':localidad'    => ['valor' => $pedido->getLocalidad()],
                ':direccion'    => ['valor' => $pedido->getDireccion()],
                ':subtotal'     => ['valor' => $pedido->getSubtotal()],
                ':impuestos'    => ['valor' => $pedido->getImpuestos()],
                ':coste_total'  => ['valor' => $pedido->getCosteTotal()],
                ':estado'       => ['valor' => $pedido->getEstado()],
                ':fecha_pedido' => ['valor' => $fechaPedido],
            ];

            $exito = $this->conexion->ejecutar($sql, $params);

            if($exito){
                $nuevoId = $this->conexion->ultimoIdInsertado();
                if($nuevoId > 0){
                    $pedido->setId($nuevoId);
                }
            }

            return $exito;

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL CREAR UN PEDIDO NUEVO: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function update(Pedido $pedido): bool
    {
        try{
            $sql = "UPDATE pedidos SET
                        usuario_id = :usuario_id,
                        provincia = :provincia,
                        localidad = :localidad,
                        direccion = :direccion,
                        subtotal = :subtotal,
                        impuestos = :impuestos,
                        coste_total = :coste_total,
                        estado = :estado,
                        fecha_pedido = :fecha_pedido
                    WHERE id = :id";

            $params = [
                ':id'           => ['valor' => $pedido->getId()],
                ':usuario_id'   => ['valor' => $pedido->getUsuarioId()],
                ':provincia'    => ['valor' => $pedido->getProvincia()],
                ':localidad'    => ['valor' => $pedido->getLocalidad()],
                ':direccion'    => ['valor' => $pedido->getDireccion()],
                ':subtotal'     => ['valor' => $pedido->getSubtotal()],
                ':impuestos'    => ['valor' => $pedido->getImpuestos()],
                ':coste_total'  => ['valor' => $pedido->getCosteTotal()],
                ':estado'       => ['valor' => $pedido->getEstado()],
                ':fecha_pedido' => ['valor' => $pedido->getFechaPedido()],
            ];

            return $this->conexion->ejecutar($sql, $params);

        } catch(PDOException $e){
            throw new RuntimeException(
                "ERROR AL ACTUALIZAR EL PEDIDO: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    public function save(Pedido $pedido): bool
    {
        return ($pedido->getId() > 0)
            ? $this->update($pedido)
            : $this->create($pedido);
    }
    
}



?>