<?php
namespace App\Shared\Container;

use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Repositories\ProductRepository;
use App\Products\Services\Contracts\ProductGetAllInterface;
use App\Products\Services\ProductGetAll;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Convert\ConvertPriceToDolar;

final class Provider{
    const BINDINGS = [
      ProductGetAllInterface::class=> ProductGetAll::class,
      ProductRepositoryInterface::class=> ProductRepository::class,
      ConvertPriceInterface::class=> ConvertPriceToDolar::class
    ];
}