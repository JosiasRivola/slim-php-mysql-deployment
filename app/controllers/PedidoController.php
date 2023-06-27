<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $newGuid = uniqid();  
    $pedido = new Pedido();
    $pedido->IdProducto = $parametros['IdProducto'];
    $pedido->Guid = isset($parametros['Guid']) ? $parametros['Guid'] : substr($newGuid, 8, 5);
    $pedido->IdMesa = $parametros['IdMesa'];
    $pedido->IdEstado = $parametros['IdEstado'];
    $pedido->IdEmpleado = $parametros['IdEmpleado'];
    $pedido->NombreCliente = $parametros['NombreCliente'];
    $pedido->Foto = $parametros['Foto'];

    $pedido->crearPedido();

    $payload = json_encode(array("mensaje" => "Pedido creado con exito con codigo: {$pedido->Guid}"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ActualizarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
  
    Pedido::ActualizarPedido($parametros['Guid'], $parametros['IdEstado'], $parametros['IdEmpleado'], $parametros['TiempoEstimado']);

    $payload = json_encode(array("mensaje" => "Pedido actualizado con exito"));

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

  public function ObtenerTodosPendientes($request, $response, $args)
  {        
    $lista = Pedido::obtenerTodosPendientesRol(1, $args['IdRol']);
    $payload = json_encode(array("listaPedido" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {    
    $parametros = $request->getParsedBody();

    $id = $parametros['Id'];
    $idEstado = $parametros['IdEstado'];
    $idEmpleado = $parametros['IdEmpleado'];
    $TiempoEstimado = $parametros['TiempoEstimado'];

    //Pedido::modificarPedido($id, $idEstado, $idEmpleado, $TiempoEstimado);

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
