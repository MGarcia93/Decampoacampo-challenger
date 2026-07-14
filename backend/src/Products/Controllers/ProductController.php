<?php
namespace App\Products\Controllers;
use App\Products\Services\Contracts\ProductGetAllInterface;
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

    public function show(Request $request, array $params): Response
    {
        $productId = (int) $params['id'];

        // Aquí iría la lógica para obtener un producto específico desde la base de datos
        $product = ['id' => $productId, 'name' => "Producto {$productId}", 'price' => 10.99];

        return Response::json($product);
    }
}