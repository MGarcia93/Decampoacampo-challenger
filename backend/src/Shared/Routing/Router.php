<?php
namespace App\Shared\Routing;

use App\Shared\Enums\MethodEnum;
final class Router
{

    private array $routes = [];



    public function routes(): array
    {
        return $this->routes;
    }
    private function add(string $path, MethodEnum $method, string $controller, string $action): void
    {
        $this->routes[$method->value][] = new Route($path, $method, $controller, $action);
    }

    public function get(string $path, string $controller, string $method): void
    {
        $this->add($path, MethodEnum::GET, $controller, $method);
    }
    public function post(string $path, string $controller, string $method): void
    {
        $this->add($path, MethodEnum::POST, $controller, $method);
    }
    public function put(string $path, string $controller, string $method): void
    {
        $this->add($path, MethodEnum::PUT, $controller, $method);
    }
    public function delete(string $path, string $controller, string $method): void
    {
        $this->add($path, MethodEnum::DELETE, $controller, $method);
    }

    public function match(string $requestMethod, string $requestPath): ?Route
    {
        $requestPathSegments = $this->getPathSegments($requestPath);
        foreach ($this->routes[$requestMethod] ?? [] as $route) {
            $parameters = [];
            $routePathSegments = $this->getPathSegments($route->path);
            if(count($routePathSegments) !== count($requestPathSegments)) {
                continue;
            }
            for($i = 0; $i < count($routePathSegments); $i++) {
                if($this->isParameter($routePathSegments[$i])) {
                  $parameterName = trim($routePathSegments[$i], '{$}');
                $parameters[$parameterName] = $requestPathSegments[$i];
                    continue;
                }
                if($routePathSegments[$i] !== $requestPathSegments[$i]) {
                    continue 2;
                }
            }
            
            return new Route($route->path, $route->method, $route->controller, $route->action,(object) $parameters);
        }
        return null;
    }

     private function getPathSegments(string $path): array
    {
        $path = trim($path, '/');

        if ($path === '') {
            return [];
        }

        return explode('/', $path);
    }

    private function isParameter(string $segment): bool
    {
        return str_starts_with($segment, '{')
            && str_ends_with($segment, '}')
            && strlen($segment) > 2;
    }
}