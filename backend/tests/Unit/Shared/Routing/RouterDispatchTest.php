<?php

use App\Shared\Container\Container;
use App\Shared\Http\Response;
use App\Shared\Routing\Router;

final class FakeRoutableControllerForDispatchTest
{
    public function show(string $id): Response
    {
        return Response::json([
            'id' => $id,
        ]);
    }
}

describe('Router dispatch collaboration', function () {
    it('resolves a matched route and calls its controller action with route parameters', function () {
        $router = new Router();
        $container = new Container();

        $router->get('/fake/{id}', FakeRoutableControllerForDispatchTest::class, 'show');

        $route = $router->match('GET', '/fake/15');

        expect($route)->not->toBeNull();

        $controller = $container->dispatch($route->controller);

        $response = $container->call(
            $controller,
            $route->action,
            (object) $route->parameters
        );

        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->statusCode)->toBe(200)
            ->and(json_decode($response->content, true))->toBe([
                'id' => '15',
            ]);
    });
});
