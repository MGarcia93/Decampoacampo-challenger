<?php
namespace App\Products\Services\Contracts;

use App\Products\Dtos\ProductResponseDto;
interface ProductShowInterface{
    public function execute(int $id): ProductResponseDto;
}