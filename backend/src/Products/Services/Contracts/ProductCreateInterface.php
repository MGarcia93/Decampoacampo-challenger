<?php
namespace App\Products\Services\Contracts;

use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Dtos\ProductResponseDto;
interface ProductCreateInterface{
    public function execute(ProductCreateRequestDto $data): ProductResponseDto;
}