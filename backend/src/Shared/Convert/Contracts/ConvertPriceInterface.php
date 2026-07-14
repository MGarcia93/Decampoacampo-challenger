<?php
namespace App\Shared\Convert\Contracts;
interface ConvertPriceInterface{
    public function convert(float $price): float;
}