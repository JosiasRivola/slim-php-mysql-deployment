<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $CantidadSillas = $parametros['CantidadSillas'];
    $IdEstado = $parametros['IdEstado'];

    $mesa = new Mesa();
    $mesa->CantidadSillas = $CantidadSillas;
    $mesa->IdEstado = $IdEstado;
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
    $id = $parametros['IdMesa'];
    $cantidadSillas = $parametros['CantidadSillas'];

    Mesa::modificarMesa($id, $cantidadSillas);

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
