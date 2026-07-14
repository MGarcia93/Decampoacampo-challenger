<?php

declare(strict_types=1);

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\ProductUpdate;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
use App\Shared\Exceptions\NotFoundException;
afterEach(function (): void {
    Mockery::close();
});

it('updates a product and returns its response dto', function (): void {
    $requestDto = new ProductCreateRequestDto(
        nombre: 'Updated keyboard',
        descripcion: 'Updated mechanical keyboard',
        precio: 2000
    );

    $currentProduct = new Product(
        1,
        'Keyboard',
        1000,
        'A mechanical keyboard',
        '2026-07-14 10:00:00',
        '2026-07-14 10:00:00'
    );

    $updatedProduct = new Product(
        1,
        'Updated keyboard',
        2000,
        'Updated mechanical keyboard',
        '2026-07-14 10:00:00',
        '2026-07-14 12:00:00'
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
        ->andReturn($currentProduct);

    $repository
        ->shouldReceive('update')
        ->once()
        ->with(1, $requestDto)
        ->andReturnTrue();

    $repository
        ->shouldReceive('getById')
        ->once()
        ->with(1)
        ->andReturn($updatedProduct);

    $converter
        ->shouldReceive('convert')
        ->once()
        ->with(2000.0)
        ->andReturn(2.0);

    $service = new ProductUpdate(
        $repository,
        $converter
    );

    $result = $service->execute(1, $requestDto);

    $expected = new ProductResponseDto(
        1,
        'Updated keyboard',
        'Updated mechanical keyboard',
        2000,
        2.0,
        '2026-07-14 10:00:00',
        '2026-07-14 12:00:00'
    );

    expect($result)
        ->toBeInstanceOf(ProductResponseDto::class)
        ->toEqual($expected);
});

it('throws a not found exception when the product does not exist', function (): void {
    $requestDto = new ProductCreateRequestDto(
        nombre: 'Updated keyboard',
        descripcion: 'Updated mechanical keyboard',
        precio: 2000
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
        ->with(99)
        ->andReturnNull();

    $repository->shouldNotReceive('update');
    $converter->shouldNotReceive('convert');

    $service = new ProductUpdate(
        $repository,
        $converter
    );

    $service->execute(99, $requestDto);
})->throws(
    NotFoundException::class,
    'Producto no encontrado'
);

it('throws a bad request exception when the product cannot be updated', function (): void {
    $requestDto = new ProductCreateRequestDto(
        nombre: 'Updated keyboard',
        descripcion: 'Updated mechanical keyboard',
        precio: 2000
    );

    $currentProduct = new Product(
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
        ->andReturn($currentProduct);

    $repository
        ->shouldReceive('update')
        ->once()
        ->with(1, $requestDto)
        ->andReturnFalse();

    $converter->shouldNotReceive('convert');

    $service = new ProductUpdate(
        $repository,
        $converter
    );

    $service->execute(1, $requestDto);
})->throws(
    BadRequestException::class,
    'Error al actualizar el producto'
);