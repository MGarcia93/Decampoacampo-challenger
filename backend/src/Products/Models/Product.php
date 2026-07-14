<?php
namespace App\Products\Models;
final class Product{
    public function __construct(
        public int $id,
        public string $nombre,
        public float $precio,
        public string $descripcion,
        public string $created_at,
        public string $updated_at
    ){}
}