<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/EmpleadoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/empleados', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
  $group->get('/{IdEmpleado}', \EmpleadoController::class . ':TraerUno');
  $group->post('[/]', \EmpleadoController::class . ':CargarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{IdProducto}', \ProductoController::class . ':TraerUno');  
  $group->post('[/]', \ProductoController::class . ':CargarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{IdMesa}', \MesaController::class . ':TraerUno');  
  $group->post('[/]', \MesaController::class . ':CargarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{IdPedido}', \PedidoController::class . ':TraerUno');  
  $group->post('[/]', \PedidoController::class . ':CargarUno');//TODO: validar para que solo el mozo pueda hacerlo se puede llamar al traerUno del Empleado para saber el rol
  $group->put('[/]', \PedidoController::class . ':ModificarUno');  
  //BorrarUno
});

$app->get('[/]', function (Request $request, Response $response) {   
  $ip = $_SERVER['REMOTE_ADDR'];
  $apiUrl = "http://ip-api.com/json/{$ip}";
  
  $resp = file_get_contents($apiUrl);
  $data = json_decode($resp);
  
  if ($data->status == 'success') {
      $country = $data->country;
      $city = $data->city;
      $latitude = $data->lat;
      $longitude = $data->lon;
  
      echo "País: " . $country . "<br>";
      echo "Ciudad: " . $city . "<br>";
      echo "Latitud: " . $latitude . "<br>";
      echo "Longitud: " . $longitude . "<br>";
  } else {
      echo "No se pudo obtener la ubicación.";
  }
  
  $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
  
  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
