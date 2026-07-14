<?php

declare(strict_types=1);

use App\Products\Dtos\ProductResponseDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\ProductGetAll;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
it('returns all products from the repository', function (): void {
    $products = [
        new Product(
            1,
            'Keyboard',
            1000,
            'A mechanical keyboard',
            '',
            ''
        ),
        new Product(
            2,
            'Mouse',
            500,
            'A mechanical keyboard',
            '',
            ''
        ),
    ];

    $repository = Mockery::mock(
        ProductRepositoryInterface::class
    );

    $converter = Mockery::mock(
        ConvertPriceInterface::class
    );

    $repository
        ->shouldReceive('getAll')
        ->once()
        ->andReturn($products);

    $converter
        ->shouldReceive('convert')
        ->once()
        ->with(1000.0)
        ->andReturn(10.0);

    $converter
        ->shouldReceive('convert')
        ->once()
        ->with(500.0)
        ->andReturn(5.0);

    $responseProducts = [
        new ProductResponseDto(
            1,
            'Keyboard',
            'A mechanical keyboard',
            1000,
            10.0,
            '',
            ''
        ),
        new ProductResponseDto(
            2,
            'Mouse',
            'A mechanical keyboard',
            500,
            5.0,
            '',
            ''
        ),
    ];

    $service = new ProductGetAll(
        $repository,
        $converter
    );

    expect($service->execute())->toEqual($responseProducts);
});