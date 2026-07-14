<?php
namespace App\Products\Dtos;

use App\Shared\Exceptions\ValidateException;
class ProductCreateRequestDto{
    public function __construct(
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly float $precio,
    ){}

    public static function fromArray(array $data): self{
        if (empty($data['nombre'])) {
            throw new ValidateException("El nombre del producto es obligatorio");
        }
        if (empty($data['descripcion'])) {
            throw new ValidateException("La descripción del producto es obligatoria");
        }
    
        if (empty($data['precio']) || !is_numeric($data['precio']) || $data['precio'] <= 0) {
            throw new ValidateException("El precio del producto debe ser mayor a cero");
        }
        return new self(
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            precio: $data['precio']
        );
    }
   
}