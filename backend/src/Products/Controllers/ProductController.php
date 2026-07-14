<?php
namespace App\Products\Controllers;
use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Services\Contracts\ProductCreateInterface;
use App\Products\Services\Contracts\ProductDeleteInterface;
use App\Products\Services\Contracts\ProductGetAllInterface;
use App\Products\Services\Contracts\ProductShowInterface;
use App\Products\Services\Contracts\ProductUpdateInterface;
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

    public function create(Request $request, ProductCreateInterface $productCreate): Response
    {

        $data = $request->getParameters();

        $productRequestDto = ProductCreateRequestDto::fromArray((array)$data);

        $productResponseDto = $productCreate->execute($productRequestDto);

        return Response::json(['data' => $productResponseDto], 201);

    }
    public function update(int $id, Request $request, ProductUpdateInterface $productUpdate): Response
    {

        $data = $request->getParameters();

        $productRequestDto = ProductCreateRequestDto::fromArray((array)$data);

        $productResponseDto = $productUpdate->execute($id, $productRequestDto);

        return Response::json(['data' => $productResponseDto], 200);

    }
    public function delete(int $id, ProductDeleteInterface $productDelete): Response
    {


        $productDelete->execute($id);

        return Response::noContent();

    }
}