<?php

declare(strict_types=1);

use App\Products\Dtos\ProductResponseDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\ProductShow;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\NotFoundException;
use Mockery;

afterEach(function (): void {
    Mockery::close();
});

it('returns the requested product', function (): void {
    $product = new Product(
        1,
        'Keyboard',
        1000,
        'A mechanical keyboard',
        '',
        ''
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

    $converter
        ->shouldReceive('convert')
        ->once()
        ->with(1000.0)
        ->andReturn(10.0);

    $service = new ProductShow(
        $repository,
        $converter
    );

    $result = $service->execute(1);

    $expected = new ProductResponseDto(
        1,
        'Keyboard',
        'A mechanical keyboard',
        1000,
        10.0,
        '',
        ''
    );

    expect($result)
        ->toBeInstanceOf(ProductResponseDto::class)
        ->toEqual($expected);
});

it('throws an exception when the product does not exist', function (): void {
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

    $converter
        ->shouldNotReceive('convert');

    $service = new ProductShow(
        $repository,
        $converter
    );

    $service->execute(99);
})->throws(
    NotFoundException::class,
    'Producto con id 99 no encontrado'
);