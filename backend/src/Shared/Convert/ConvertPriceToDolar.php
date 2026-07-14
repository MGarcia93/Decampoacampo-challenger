<?php
namespace App\Shared\Convert;

use App\Shared\Convert\Contracts\ConvertPriceInterface;

class ConvertPriceToDolar implements ConvertPriceInterface{
    private readonly float $precio_usd;

    public function __construct(){
        $this->precio_usd=(float)getenv('PRECIO_USD');
    }
    public function convert(float $price): float{
        return  $price / $this->precio_usd;
    }
}