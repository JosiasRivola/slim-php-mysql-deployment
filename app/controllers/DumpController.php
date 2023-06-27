<?php
require_once './models/Dump.php';
require_once './interfaces/IApiUsable.php';

class DumpController extends Dump implements IApiUsable
{
  public function ExportarDump($request, $response, $args)
  {
    Dump::Exportar();

    $payload = json_encode(array("mensaje" => "Exportado con exito"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ImportarDump($request, $response, $args)
  {
    Dump::Importar();
    
    $payload = json_encode(array("mensaje" => "Importado con exito"));
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function CargarUno($request, $response, $args)
  {        
  }

  public function TraerUno($request, $response, $args)
  {   
  }

  public function TraerTodos($request, $response, $args)
  {
  }

  public function ModificarUno($request, $response, $args)
  {
  }

  public function BorrarUno($request, $response, $args)
  {
  }
}
