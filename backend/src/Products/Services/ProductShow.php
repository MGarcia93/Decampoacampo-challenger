<?php
namespace App\Products\Services;

use App\Products\Dtos\ProductResponseDto;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\Contracts\ProductShowInterface;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\NotFoundException;
class ProductShow implements ProductShowInterface{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ConvertPriceInterface $convertPriceInterface)
    {
    }
    public function execute(int $id): ProductResponseDto{
        $product= $this->productRepository->getById($id);
        if (!$product) {
            throw new NotFoundException("Producto con id $id no encontrado");
        }
        return new ProductResponseDto(
            $product->id,
            $product->nombre,
            $product->descripcion,
            $product->precio,
            $this->convertPriceInterface->convert($product->precio),
            $product->created_at,
            $product->updated_at
        );
    }
}