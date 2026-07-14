<?php

declare(strict_types=1);

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\ProductCreate;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
use Mockery;

afterEach(function (): void {
    Mockery::close();
});

it('creates a product and returns its response dto', function (): void {
    $requestDto = new ProductCreateRequestDto(
        nombre: 'Keyboard',
        descripcion: 'A mechanical keyboard',
        precio: 1000
    );

    $createdProduct = new Product(
        1,
        'Keyboard',
        1000,
        'A mechanical keyboard',
        '2026-07-13 10:00:00',
        '2026-07-13 10:00:00'
    );

    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('create')
        ->once()
        ->with($requestDto)
        ->andReturn($createdProduct);

    $converter
        ->shouldReceive('convert')
        ->once()
        ->with(1000.0)
        ->andReturn(1.0);

    $service = new ProductCreate(
        $repository,
        $converter
    );

    $result = $service->execute($requestDto);

    $expected = new ProductResponseDto(
        1,
        'Keyboard',
        'A mechanical keyboard',
        1000,
        1.0,
        '2026-07-13 10:00:00',
        '2026-07-13 10:00:00'
    );

    expect($result)
        ->toBeInstanceOf(ProductResponseDto::class)
        ->toEqual($expected);
});

it('throws a bad request exception when the product cannot be created', function (): void {
    $requestDto = new ProductCreateRequestDto(
        nombre: 'Keyboard',
        descripcion: 'A mechanical keyboard',
        precio: 1000
    );

    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('create')
        ->once()
        ->with($requestDto)
        ->andReturnNull();

    $converter->shouldNotReceive('convert');

    $service = new ProductCreate(
        $repository,
        $converter
    );

    $service->execute($requestDto);
})->throws(
    BadRequestException::class,
    'Error al crear el producto'
);