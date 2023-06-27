<?php

use Illuminate\Support\Arr;

class Dump
{
    public function Exportar()
    {
        $archivo = './archivo.csv';
        $archivo = fopen($archivo, 'w');

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from empleados");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from estadoroles");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);        
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from estados");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from mesas");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from pedidos");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from productos");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from roles");              
        $consulta->execute();
        $lista = $consulta->fetchAll(PDO::FETCH_OBJ);
        Dump::CargarLista($archivo, $lista);        
        fclose($archivo);        
        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public function Importar()
    {
        $archivo = './archivo.csv';
        $archivo = fopen($archivo, 'r');
        $separador = "//////////\n";
        $listaEmpleados = array();
        $listaEstadoRoles = array();
        $listaEstados = array();
        $listaMesas = array();
        $listaPedidos = array();
        $listaProductos = array();
        $listaRoles = array();
        $currentArray = &$listaEmpleados;

        while (($linea = fgets($archivo)) !== false) {                        
            if ($linea == $separador) {                
                if ($currentArray == $listaEmpleados) {
                    $currentArray = &$listaEstadoRoles;
                }
                elseif ($currentArray == $listaEstadoRoles) {
                    $currentArray = &$listaEstados;
                }
                elseif ($currentArray == $listaEstados) {
                    $currentArray = &$listaMesas;
                }
                elseif ($currentArray == $listaMesas) {
                    $currentArray = &$listaPedidos;
                }
                elseif ($currentArray == $listaPedidos) {
                    $currentArray = &$listaProductos;
                }
                elseif ($currentArray == $listaProductos) {
                    $currentArray = &$listaRoles;
                }
                else {                
                    break;
                }
            }
            else {                
                array_push($currentArray, $linea);
            }
        }

        foreach ($listaEmpleados as $fila) {
            $values = explode(",", $fila);                        
            Empleado::CrearEmpleado($values[1], $values[2]);
        }

        foreach ($listaEstadoRoles as $fila) {
            $values = explode(",", $fila);                                    
             $objAccesoDatos = AccesoDatos::obtenerInstancia();
             $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO estadoroles (IdEstado, IdRol) VALUES ($values[0], $values[1])");
            $consulta->execute();
        }
        
        foreach ($listaEstados as $fila) {
            $values = explode(",", $fila);                                    
             $objAccesoDatos = AccesoDatos::obtenerInstancia();
             $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO estadoroles (IdEstado, IdRol) VALUES ($values[0], $values[1])");
            $consulta->execute();
        }

        foreach ($listaMesas as $fila) {
            $values = explode(",", $fila);
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            echo "INSERT INTO mesas (CodigoMesa, IdEstado) VALUES ($values[1], $values[2]) <br>";             
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (CodigoMesa, IdEstado) VALUES (:codigoMesa, :idEstado)");
            $consulta->bindValue(':codigoMesa', $values[1], PDO::PARAM_STR);
            $consulta->bindValue(':IdEstado', $values[2], PDO::PARAM_INT);
            $consulta->execute();
        }

        foreach ($listaPedidos as $fila) {
            $values = explode(",", $fila);
            $objAccesoDatos = AccesoDatos::obtenerInstancia();            
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (IdProducto, Fecha, IdEstado, Guid, IdMesa, IdEmpleado, NombreCliente,
                                                        TiempoEstimado, Foto, FechaBaja) 
                                                        VALUES (:idProducto, :fecha, :idEstado, :guid, :idMesa, :idEmpleado, :nombreCliente,
                                                        :tiempoEstimado, :foto, :fechaBaja)");
            
            $consulta->bindValue(':idProducto', $values[1], PDO::PARAM_INT);
            $consulta->bindValue(':fecha', $values[2], PDO::PARAM_STR);
            $consulta->bindValue(':idEstado', $values[3], PDO::PARAM_INT);
            $consulta->bindValue(':guid', $values[4], PDO::PARAM_STR);        
            $consulta->bindValue(':idMesa', $values[5], PDO::PARAM_INT);
            $consulta->bindValue(':idEmpleado', $values[6], PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $values[7], PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimado', $values[8], PDO::PARAM_INT);
            $consulta->bindValue(':foto', $values[9], PDO::PARAM_STR);
            $consulta->bindValue(':fechaBaja', $values[10], PDO::PARAM_STR);                        
            $consulta->execute();        
        }

        foreach ($listaProductos as $fila) {
            $values = explode(",", $fila);                                                
            $p = New Producto();
            $p->Nombre = $values[1];
            $p->Precio = $values[2];
            $p->IdRol = $values[3];
            $p->crearProducto();
        }

        foreach ($listaRoles as $fila) {
            $values = explode(",", $fila);                                    
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO roles (Descripcion) VALUES (:descripcion)");
            // $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO roles (Id, Descripcion) VALUES (:id, :descripcion)");
            // $consulta->bindValue(':id', $values[0], PDO::PARAM_INT);
            $consulta->bindValue(':descripcion', $values[1], PDO::PARAM_STR);
            $consulta->execute();
        }    
    }    
    
    public static function CargarLista($archivo, $lista){
        foreach ($lista as $value) {
            $fila = get_object_vars($value);
            fputcsv($archivo, $fila);
        }
        fwrite($archivo, "//////////\n");    
    }
}
