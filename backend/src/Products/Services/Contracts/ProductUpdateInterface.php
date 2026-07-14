<?php
namespace App\Products\Services\Contracts;

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
interface ProductUpdateInterface{
    public function execute(int $id, ProductCreateRequestDto $data): ProductResponseDto;
}