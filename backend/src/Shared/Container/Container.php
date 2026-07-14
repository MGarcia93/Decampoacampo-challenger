<?php

declare(strict_types=1);

namespace App\Shared\Container;

use App\Shared\Database\Connection;
use App\Shared\Http\Request;
use App\Shared\Routing\Route;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use stdClass;

final class Container
{
    public function dispatch(string $class): object
    {
        $class = $this->resolveImplementation($class);
        if ($class === Connection::class) {
            return Connection::getInstance();
        }


        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                "La clase {$class} no se puede instanciar."
            );
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->resolveDependency(
                $class,
                $parameter
            );
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    public function call(
        object $controller,
        string $method,
        ?stdClass $parameters = null,
    ): mixed {
        $reflectionMethod = new ReflectionMethod(
            $controller,
            $method
        );

        if (!$reflectionMethod->isPublic()) {
            throw new RuntimeException(
                "El método {$method} debe ser público."
            );
        }


        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $parameter) {
            $arguments[] = $this->resolveMethodParameter(
                $parameter,
                $parameters
            );
        }

        return $reflectionMethod->invokeArgs(
            $controller,
            $arguments
        );
    }

    private function resolveDependency(
        string $class,
        ReflectionParameter $parameter
    ): object {
        $type = $parameter->getType();

        if (
            !$type instanceof ReflectionNamedType
            || $type->isBuiltin()
        ) {
            throw new RuntimeException(
                "El constructor de {$class} solo puede recibir clases o interfaces. "
                . "No se pudo resolver \${$parameter->getName()}."
            );
        }

        return $this->dispatch(
            $type->getName()
        );
    }

    private function resolveMethodParameter(
        ReflectionParameter $parameter,
        ?stdClass $parameters = null
    ): mixed {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException(
                "No se puede resolver el parámetro "
                . "\${$parameter->getName()}."
            );
        }

        if (!$type->isBuiltin()) {
            if ($type->getName() === Request::class) {
                return new Request($parameters);
            }

            return $this->dispatch(
                $type->getName()
            );
        }

        return $this->resolvePrimitive(
            $parameter,
            $parameters
        );
    }

    private function resolvePrimitive(
        ReflectionParameter $parameter,
        ?stdClass $parameters = null
    ): mixed {
        $parameterName = $parameter->getName();
        $value = $parameters->{$parameterName} ?? null;

        if ($value !== null) {
            return $value;
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new RuntimeException(
            "No se pudo resolver el parámetro "
            . "\${$parameterName}."
        );
    }


    private function resolveImplementation(string $class): string
    {
        return Provider::BINDINGS[$class] ?? $class;
    }
}