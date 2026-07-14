<?php
namespace App\Products\Services;

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\Contracts\ProductCreateInterface;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
class ProductCreate implements ProductCreateInterface{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ConvertPriceInterface $convertPriceInterface)
    {
    }
    public function execute(ProductCreateRequestDto $data): ProductResponseDto{
        $product= $this->productRepository->create($data);
        if (!$product) {
            throw new BadRequestException("Error al crear el producto");
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