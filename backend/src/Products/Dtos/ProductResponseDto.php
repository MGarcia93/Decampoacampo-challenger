<?php
namespace App\Products\Dtos;
class ProductResponseDto{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly float $precio,
        public readonly float $precio_usd,
        public readonly string $created_at,
        public readonly string $updated_at
    ){}
}