<?php
namespace App\Products\Repositories\Contracts;
use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Models\Product;
use App\Shared\Dtos\Paginate;
interface ProductRepositoryInterface{
    public function getAll(): array;
    public function getById(int $id): ?Product;
    public function create(ProductCreateRequestDto $data): ?Product;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}