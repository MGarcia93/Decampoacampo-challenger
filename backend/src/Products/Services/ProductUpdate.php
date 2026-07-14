<?php
namespace App\Products\Services;

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\Contracts\ProductUpdateInterface;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
use App\Shared\Exceptions\NotFoundException;
class ProductUpdate implements ProductUpdateInterface{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ConvertPriceInterface $convertPriceInterface)
    {
    }
    public function execute(int $id, ProductCreateRequestDto $data): ProductResponseDto{
        $product= $this->productRepository->getById($id);
    
        if (!$product) {
            throw new NotFoundException("Producto no encontrado");
        }
        if(!$this->productRepository->update($id, $data)){
            throw new BadRequestException("Error al actualizar el producto");
        }
        $product= $this->productRepository->getById($id);
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