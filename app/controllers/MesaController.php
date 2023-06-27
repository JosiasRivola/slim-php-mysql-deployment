<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $newGuid = uniqid();      
    $parametros = $request->getParsedBody();        
    $mesa = new Mesa();
    $mesa->CodigoMesa = isset($parametros['CodigoMesa']) ? $parametros['CodigoMesa'] : substr($newGuid, 8, 5);
    $mesa->crearMesa();

    $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $IdMesa = $args['IdMesa'];
    $mesa = Mesa::obtenerMesa($IdMesa);
    $payload = json_encode($mesa);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $codigoMesa = $parametros['CodigoMesa'];
    $idEstado = $parametros['IdEstado'];

    Mesa::modificarMesa($codigoMesa, $idEstado);

    $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $idMesa = $parametros['IdMesa'];
    Mesa::borrarMesa($idMesa);

    $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
