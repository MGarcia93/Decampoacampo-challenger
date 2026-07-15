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
        $errorMessages = [];
        if (empty($data['nombre'])) {
            $errorMessages['nombre'] = "El nombre del producto es obligatorio";
        }
        if (empty($data['descripcion'])) {
            $errorMessages['descripcion'] = "La descripción del producto es obligatoria";
        }
        if(empty($data['precio']) ) {
            $errorMessages['precio'] = "El precio del producto es obligatorio";
        }else if ( !is_numeric($data['precio']) ) {
            $errorMessages['precio'] = "El precio del producto debe ser un número";
        }else if ( $data['precio'] <= 0 ) {
            $errorMessages['precio'] = "El precio del producto debe ser mayor a cero";
        }
        if (!empty($errorMessages)) {
            throw new ValidateException(json_encode($errorMessages));
        }

        return new self(
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            precio: $data['precio']
        );
    }
   
}