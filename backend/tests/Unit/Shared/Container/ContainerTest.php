<?php

use App\Shared\Container\Container;
use App\Shared\Http\Request;


class CT_NoConstructorController
{
}

class CT_InnerService
{
}

class CT_DependsOnInner
{
    public function __construct(public CT_InnerService $inner)
    {
    }
}

abstract class CT_AbstractController
{
}

class CT_WithBuiltinParam
{
    public function __construct(public string $name)
    {
    }
}

class CT_FakeActionController
{
    public function noArgs(): string
    {
        return 'ok';
    }

    public function withRequest(Request $req): string
    {
        return $req->getParameter('name');
    }

    public function withClassDep(CT_InnerService $service): CT_InnerService
    {
        return $service;
    }

    public function withPrimitive(string $id): string
    {
        return $id;
    }

    public function withDefault(int $page = 10): int
    {
        return $page;
    }

    public function withNullable(?string $name): ?string
    {
        return $name;
    }

    protected function secret(): void
    {
    }
}


describe('Container::dispatch()', function () {
    it('instantiates a class with no constructor', function () {
        $container = new Container();
        $instance = $container->dispatch(CT_NoConstructorController::class);

        expect($instance)->toBeInstanceOf(CT_NoConstructorController::class);
    });

    it('resolves recursive class dependencies', function () {
        $container = new Container();
        $instance = $container->dispatch(CT_DependsOnInner::class);

        expect($instance)->toBeInstanceOf(CT_DependsOnInner::class);
        expect($instance->inner)->toBeInstanceOf(CT_InnerService::class);
    });

    it('falls back to the class itself when Provider::BINDINGS has no entry', function () {
        $container = new Container();
        $instance = $container->dispatch(CT_InnerService::class);

        expect($instance)->toBeInstanceOf(CT_InnerService::class);
    });

    it('throws RuntimeException for an abstract class', function () {
        $container = new Container();

        expect(fn () => $container->dispatch(CT_AbstractController::class))
            ->toThrow(RuntimeException::class, 'no se puede instanciar');
    });

    it('throws RuntimeException when a constructor parameter is a built-in type', function () {
        $container = new Container();

        expect(fn () => $container->dispatch(CT_WithBuiltinParam::class))
            ->toThrow(RuntimeException::class, 'solo puede recibir clases o interfaces');
    });
});


describe('Container::call()', function () {
    $snapshot = [];

    beforeEach(function () use (&$snapshot) {
        $snapshot = [
            '_SERVER' => $_SERVER,
            '_GET'    => $_GET,
            '_POST'   => $_POST,
        ];

        $_SERVER = ['REQUEST_URI' => '/'];
        $_GET    = [];
        $_POST   = [];
    });

    afterEach(function () use (&$snapshot) {
        $_SERVER = $snapshot['_SERVER'];
        $_GET    = $snapshot['_GET'];
        $_POST   = $snapshot['_POST'];
    });

    it('executes a public method with no arguments', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        $result = $container->call($controller, 'noArgs');

        expect($result)->toBe('ok');
    });

    it('injects a Request built from stdClass properties', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();
        $params = (object) ['name' => 'test-user'];

        $result = $container->call($controller, 'withRequest', $params);

        expect($result)->toBe('test-user');
    });

    it('resolves class dependencies via dispatch()', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        $result = $container->call($controller, 'withClassDep');

        expect($result)->toBeInstanceOf(CT_InnerService::class);
    });

    it('passes primitive parameters from stdClass properties', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();
        $params = (object) ['id' => '42'];

        $result = $container->call($controller, 'withPrimitive', $params);

        expect($result)->toBe('42');
    });

    it('falls back to the default value when stdClass has no matching property', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        $result = $container->call($controller, 'withDefault');

        expect($result)->toBe(10);
    });

    it('passes null for a nullable parameter without a value', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        $result = $container->call($controller, 'withNullable');

        expect($result)->toBeNull();
    });

    it('throws RuntimeException for an unresolvable required primitive', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        expect(fn () => $container->call($controller, 'withPrimitive'))
            ->toThrow(RuntimeException::class, 'No se pudo resolver');
    });

    it('throws RuntimeException for a non-public method', function () {
        $container = new Container();
        $controller = new CT_FakeActionController();

        expect(fn () => $container->call($controller, 'secret'))
            ->toThrow(RuntimeException::class, 'debe ser público');
    });
});
