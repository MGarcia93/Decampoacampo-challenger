<?php
namespace App\Products\Services\Contracts;

use App\Products\Dtos\ProductResponseDto;
interface ProductDeleteInterface{
    public function execute(int $id): void;
}