<?php

use App\Shared\Enums\MethodEnum;
use App\Shared\Routing\Router;

describe('Router', function () {
    it('stores a single GET route and exposes it via routes()', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');

        $routes = $router->routes();

        expect($routes)->toHaveKey('GET');
        expect($routes['GET'])->toHaveLength(1);
        expect($routes['GET'][0]->path)->toBe('/users');
    });

    it('stores GET and POST routes separately', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');
        $router->post('/users', 'FakeController', 'store');

        $routes = $router->routes();

        expect($routes)->toHaveKey('GET');
        expect($routes)->toHaveKey('POST');
        expect($routes['GET'])->toHaveLength(1);
        expect($routes['POST'])->toHaveLength(1);
    });

    it('matches a registered literal path and preserves handler metadata', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');

        $result = $router->match('GET', '/users');

        expect($result)->not->toBeNull();
        expect($result->path)->toBe('/users');
        expect($result->method)->toBe(MethodEnum::GET);
        expect($result->controller)->toBe('FakeController');
        expect($result->action)->toBe('index');
    });

    it('normalizes trailing slash when resolving a match', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');

        $result = $router->match('GET', '/users/');

        expect($result)->not->toBeNull();
        expect($result->path)->toBe('/users');
    });

    it('matches root path route registered at "/"', function () {
        $router = new Router();
        $router->get('/', 'FakeController', 'index');

        $result = $router->match('GET', '/');

        expect($result)->not->toBeNull();
    });

    it('extracts a single path segment parameter and preserves handler metadata', function () {
        $router = new Router();
        $router->get('/users/{id}', 'FakeController', 'show');

        $result = $router->match('GET', '/users/42');

        expect($result)->not->toBeNull();
        expect($result->parameters)->toHaveKey('id');
        expect($result->parameters->id)->toBe('42');
        expect($result->method)->toBe(MethodEnum::GET);
        expect($result->controller)->toBe('FakeController');
        expect($result->action)->toBe('show');
    });

    it('extracts multiple path segment parameters', function () {
        $router = new Router();
        $router->get('/users/{userId}/posts/{postId}', 'FakeController', 'show');

        $result = $router->match('GET', '/users/5/posts/9');

        expect($result)->not->toBeNull();
        expect($result->parameters)->toHaveKey('userId');
        expect($result->parameters->userId)->toBe('5');
        expect($result->parameters)->toHaveKey('postId');
        expect($result->parameters->postId)->toBe('9');
    });

    it('returns null when no route matches the path', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');

        $result = $router->match('GET', '/products');

        expect($result)->toBeNull();
    });

    it('returns null when the HTTP method does not match', function () {
        $router = new Router();
        $router->get('/users', 'FakeController', 'index');

        $result = $router->match('POST', '/users');

        expect($result)->toBeNull();
    });

    it('returns null when request segment count differs from route', function () {
        $router = new Router();
        $router->get('/users/{id}', 'FakeController', 'show');

        $result = $router->match('GET', '/users');

        expect($result)->toBeNull();
    });
});
