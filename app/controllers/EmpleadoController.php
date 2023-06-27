<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();    
    Empleado::CrearEmpleado($parametros['Nombre'], $parametros['IdRol']);

    $payload = json_encode(array("mensaje" => "Empleado creado con exito"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {    
    $empleado = Empleado::obtenerEmpleado($args['IdEmpleado']);
    $payload = json_encode($empleado);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Empleado::obtenerTodos();
    $payload = json_encode(array("listaEmpleado" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $param = $request->getParsedBody();
    Empleado::modificarEmpleado($param['Nombre'], $param['IdRol'], $param['Id']);

    $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    Empleado::borrarEmpleado($parametros['Id']);

    $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
