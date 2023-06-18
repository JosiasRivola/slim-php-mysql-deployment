<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['Nombre'];
    $precio = $parametros['Precio'];

    $prod = new Producto();
    $prod->Nombre = $nombre;
    $prod->Precio = $precio;
    $prod->crearProducto();

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {    
    $IdProducto = $args['IdProducto'];
    $prod = Producto::obtenerProducto($IdProducto);
    $payload = json_encode($prod);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProducto" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $id = $parametros['IdProducto'];
    $precio = $parametros['Precio'];

    Producto::modificarProducto($id, $precio);

    $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $idProducto = $parametros['IdProducto'];
    Producto::borrarProducto($idProducto);

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
