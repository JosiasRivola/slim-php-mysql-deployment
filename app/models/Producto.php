<?php

class Producto
{
    public $Id;
    public $Nombre;
    public $Precio;
    public $IdRol;
    public $FechaBaja;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (Nombre, Precio, IdRol) VALUES (:nombre, :precio, :idRol)");
        $consulta->bindValue(':nombre', $this->Nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->Precio);
        $consulta->bindValue(':idRol', $this->IdRol, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.Id,
                                                        P.Nombre,
                                                        P.Precio, 
                                                        R.Descripcion 'Rol' 
                                                        FROM productos P 
                                                        INNER JOIN roles R 
                                                        on P.IdRol = R.Id 
                                                        Order by P.Id;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($id, $precio)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET precio = :precio WHERE id = :id");
        $consulta->bindValue(':precio', $precio);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET FechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
