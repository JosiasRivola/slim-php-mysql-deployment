<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class ValidarEstadoRol
{
    public function __invoke(Request $request, RequestHandler $handler): Response {                        
        $parametros = $request->getParsedBody();
        $IdEmpleado = $parametros["IdEmpleado"];
        $IdEstado = $parametros["IdEstado"];

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 from empleados E 
                                                        INNER JOIN estadoroles ER ON E.IdRol = ER.IdRol 
                                                        WHERE E.Id = :IdEmpleado 
                                                        AND ER.IdEstado = :IdEstado;");
        $consulta->bindValue(':IdEmpleado', $IdEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':IdEstado', $IdEstado, PDO::PARAM_INT);
        $consulta->execute();
        
        $resultado = $consulta->fetchColumn();
        if ($resultado) {

            $response = $handler->handle($request);
                        
            $existingContent = json_decode($response->getBody());            
            return $response->withStatus(200);
        }
        else {
            $response = new Response();
            $response->getBody()->write("No está habilitado para realizar esta acción");
            return $response->withStatus(403);
        }
        
    }
}
