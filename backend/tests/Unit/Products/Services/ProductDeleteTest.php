<?php

declare(strict_types=1);

use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\ProductDelete;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
use App\Shared\Exceptions\NotFoundException;
use Mockery;

afterEach(function (): void {
    Mockery::close();
});

it('deletes an existing product', function (): void {
    $product = new Product(
        1,
        'Keyboard',
        1000,
        'A mechanical keyboard',
        '2026-07-14 10:00:00',
        '2026-07-14 10:00:00'
    );

    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('getById')
        ->once()
        ->with(1)
        ->andReturn($product);

    $repository
        ->shouldReceive('delete')
        ->once()
        ->with(1)
        ->andReturnTrue();

    $converter->shouldNotReceive('convert');

    $service = new ProductDelete(
        $repository,
        $converter
    );

    $result = $service->execute(1);

    expect($result)->toBeNull();
});

it('throws a not found exception when the product does not exist', function (): void {
    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('getById')
        ->once()
        ->with(99)
        ->andReturnNull();

    $repository->shouldNotReceive('delete');
    $converter->shouldNotReceive('convert');

    $service = new ProductDelete(
        $repository,
        $converter
    );

    $service->execute(99);
})->throws(
    NotFoundException::class,
    'Producto no encontrado'
);

it('throws a bad request exception when the product cannot be deleted', function (): void {
    $product = new Product(
        1,
        'Keyboard',
        1000,
        'A mechanical keyboard',
        '2026-07-14 10:00:00',
        '2026-07-14 10:00:00'
    );

    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('getById')
        ->once()
        ->with(1)
        ->andReturn($product);

    $repository
        ->shouldReceive('delete')
        ->once()
        ->with(1)
        ->andReturnFalse();

    $converter->shouldNotReceive('convert');

    $service = new ProductDelete(
        $repository,
        $converter
    );

    $service->execute(1);
})->throws(
    BadRequestException::class,
    'Error al eliminar el producto'
);