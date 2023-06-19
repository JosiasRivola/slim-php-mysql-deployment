<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $pedido = new Pedido();
    $pedido->IdProducto = $parametros['IdProducto'];
    $pedido->Guid = substr(uniqid(), 0, 5);
    $pedido->IdMesa = $parametros['IdMesa'];    
    $pedido->IdEmpleado = $parametros['IdEmpleado'];
    $pedido->NombreCliente = $parametros['NombreCliente'];
    $pedido->Foto = $parametros['Foto'];

    $pedido->crearPedido();

    $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $IdPedido = $args['IdPedido'];
    $pedido = Pedido::obtenerPedido($IdPedido);
    $payload = json_encode($pedido);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Pedido::obtenerTodos();
    $payload = json_encode(array("listaPedido" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {

    $requestBody = file_get_contents("php://input");
    $parametros = json_decode($requestBody, true);
    
    //$parametros = $request->getParsedBody();
    $id = $parametros['Id'];
    $idEstado = $parametros['IdEstado'];
    $idEmpleado = $parametros['IdEmpleado'];

    Pedido::modificarPedido($id, $idEstado, $idEmpleado);

    $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $idPedido = $parametros['Guid'];
    Pedido::borrarPedido($idPedido);

    $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
