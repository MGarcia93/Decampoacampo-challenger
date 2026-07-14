<?php

require __DIR__ . '/vendor/autoload.php';
use App\Shared\Exceptions\HttpException;
use App\Shared\Http\Response;
use App\Shared\Routing\Router;
use App\Shared\Container\Container;
class App
{
    public function run()
    {
        try{

            $router = new Router();
            $container = new Container();
            require __DIR__ . '/Routes/api.php';
            $_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $route = $router->match($requestMethod, $requestPath);
            if (!$route) {
                $this->error("Ruta no encontrada con $requestMethod $requestPath.", 404);
            }
            $controllerClass = $route->controller;
            $actionMethod = $route->action;
            if (!class_exists($controllerClass)) {
                $this->error("Clase de controlador $controllerClass no encontrada.", 500);
            }
            $controller =   $container->dispatch($controllerClass);
            if (!method_exists($controller, $actionMethod)) {
                $this->error("Método $actionMethod no encontrado en la clase de controlador $controllerClass.", 500);
            }
            $response=$container->call($controller, $actionMethod, $route->parameters);
            $response->send();
        }catch (HttpException $e) {
            $this->error($e->getMessage(), $e->getStatusCode());
        }catch (\Throwable $e) {
            $this->error($e->getMessage(), 500);
        }
       
        



    }
    public function error(string $message, int $statusCode = 500): void
    {
        Response::json(['error' => $message, 'code' => $statusCode], $statusCode)->send();
        
        die();
    }
}
return new App();