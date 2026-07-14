<?php
namespace App\Products\Controllers;
use App\Products\Services\Contracts\ProductGetAllInterface;
use App\Products\Services\Contracts\ProductShowInterface;
use App\Shared\Exceptions\ValidateException;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
class ProductController
{
    public function index(ProductGetAllInterface $productGetAll): Response
    {
        $products = $productGetAll->execute();


        return Response::json([
            'data' => $products
        ]);
    }

    public function show(int $id, ProductShowInterface $productShow): Response
    {
        $product = $productShow->execute($id);

        return Response::json(['data' => $product]);
    }
}