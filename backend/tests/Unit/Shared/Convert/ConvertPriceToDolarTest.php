<?php

declare(strict_types=1);

use App\Shared\Convert\ConvertPriceToDolar;

beforeEach(function (): void {
    putenv('PRECIO_USD=1000');
});

afterEach(function (): void {
    putenv('PRECIO_USD');
});

it('converts a price from pesos to dollars', function (): void {
    $converter = new ConvertPriceToDolar();

    $result = $converter->convert(50_000);

    expect($result)->toBe(50.0);
});

it('returns zero when the price is zero', function (): void {
    $converter = new ConvertPriceToDolar();

    expect($converter->convert(0))->toBe(0.0);
});

it('converts prices with decimal values', function (): void {
    $converter = new ConvertPriceToDolar();

    $result = $converter->convert(1_500.50);

    expect($result)->toBe(1.50);
});