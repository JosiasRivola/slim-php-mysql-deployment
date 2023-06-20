<?php

class Pedido
{
    public $Id;
    public $IdProducto;
    public $Fecha;
    public $IdEstado;
    public $Guid;
    public $IdMesa;
    public $IdEmpleado;
    public $NombreCliente;
    public $Foto;
    public $FechaBaja;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedidos (IdProducto, Fecha, IdEstado, Guid, IdMesa, IdEmpleado, NombreCliente, Foto) 
                                                        VALUES (:idProducto, :fecha, :idEstado, :guid, :idMesa, :idEmpleado, :nombreCliente, :foto)");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':idProducto', $this->IdProducto, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':idEstado', $this->IdEstado, PDO::PARAM_INT);
        $consulta->bindValue(':guid', $this->Guid, PDO::PARAM_STR);        
        $consulta->bindValue(':idMesa', $this->IdMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $this->IdEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->NombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->Foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos1()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.Id,
        P.Fecha, 
        P.Guid,
        PR.Nombre  \"Producto\", 
        E.Descripcion \"Estado\", 
        EMP.Nombre \"Empleado\", 
        R.Descripcion \"Rol\", 
        P.NombreCliente, 
        P.Foto
        FROM pedidos P 
        INNER JOIN productos PR ON P.IdProducto = PR.Id
        INNER JOIN estados E ON P.IdEstado = E.Id
        INNER JOIN empleados EMP ON P.IdEmpleado = EMP.Id
        INNER JOIN roles R ON EMP.IdRol = R.Id;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    // public static function obtenerTodosRol($idRol)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();

    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos WHERE");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    // }

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($id, $idEstado, $idEmpleado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET IdEstado = :idEstado , IdEmpleado = :idEmpleado WHERE Id = :id");
        $consulta->bindValue(':idEstado', $idEstado, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($idGuid)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET FechaBaja = :fechaBaja WHERE guid = :idGuid");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':idGuid', $idGuid, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
