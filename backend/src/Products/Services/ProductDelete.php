<?php
namespace App\Products\Services;

use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Products\Services\Contracts\ProductDeleteInterface;
use App\Shared\Convert\Contracts\ConvertPriceInterface;
use App\Shared\Exceptions\BadRequestException;
use App\Shared\Exceptions\NotFoundException;
class ProductDelete implements ProductDeleteInterface{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ConvertPriceInterface $convertPriceInterface)
    {
    }
    public function execute(int $id):void{
        $product= $this->productRepository->getById($id);
        if (!$product) {
            throw new NotFoundException("Producto no encontrado");
        }
        if(!$this->productRepository->delete($id)) {
            throw new BadRequestException("Error al eliminar el producto");
        }
    }
}