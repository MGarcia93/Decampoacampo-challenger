<?php
namespace App\Products\Services;

use App\Products\Dtos\ProductResponseDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\Contracts\ProductGetAllInterface;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
class ProductGetAll implements ProductGetAllInterface{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ConvertPriceInterface $convertPriceInterface)
    {
    }
    public function execute(): array{
        $products= $this->productRepository->getAll()??[];
        return array_map(fn(Product $product) => new ProductResponseDto(
            $product->id,
            $product->nombre,
            $product->descripcion,
            $product->precio,
            $this->convertPriceInterface->convert($product->precio),
            $product->created_at,
            $product->updated_at
        ), $products);
    }
}