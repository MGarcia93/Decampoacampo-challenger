<?php

declare(strict_types=1);

use App\Shared\Database\Connection;

it('returns the same Connection instance', function (): void {
    $first = Connection::getInstance();
    $second = Connection::getInstance();

    expect($first)->toBe($second);
});

it('returns a PDO connection', function (): void {
    $connection = Connection::getInstance();

    expect($connection->getConnection())
        ->toBeInstanceOf(PDO::class);
});

it('keeps the same PDO instance', function (): void {
    $connection = Connection::getInstance();

    $first = $connection->getConnection();
    $second = $connection->getConnection();

    expect($first)->toBe($second);
});

it('can execute a SELECT query', function (): void {
    $result = Connection::getInstance()->executeQuery(
        'SELECT 1 AS result'
    );

    expect($result)
        ->toBeArray()
        ->toHaveCount(1)
        ->and((int) $result[0]['result'])
        ->toBe(1);
});

it('can execute a prepared query with parameters', function (): void {
    $result = Connection::getInstance()->executeQuery(
        'SELECT :value AS result',
        ['value' => 15]
    );

    expect((int) $result[0]['result'])->toBe(15);
});

it('configures PDO to throw exceptions', function (): void {
    $pdo = Connection::getInstance()->getConnection();

    expect($pdo->getAttribute(PDO::ATTR_ERRMODE))
        ->toBe(PDO::ERRMODE_EXCEPTION);
});

it('configures PDO to return associative arrays', function (): void {
    $pdo = Connection::getInstance()->getConnection();

    expect($pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE))
        ->toBe(PDO::FETCH_ASSOC);
});