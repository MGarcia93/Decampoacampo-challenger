<?php
namespace App\Products\Repositories;
use App\Products\Dtos\ProductCreateRequestDto;
use App\Products\Models\Product;
use App\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Shared\Database\Connection;
use App\Shared\Dtos\Paginate;
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    
    public function getAll(): array
    {
        $query = "SELECT * FROM productos";
        $result = $this->connection->executeQuery($query);
        return array_map(fn($row) => new Product(...$row), $result);
    }
    public function getById(int $id): ?Product
    {
        $query = "SELECT * FROM productos WHERE id = :id";
        $result = $this->connection->executeQuery($query, ['id' => $id]);
        return $result ? new Product(...$result[0]) : null;
    }
    public function create(ProductCreateRequestDto $data): ?Product
    {
        $query = "INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)";
        $result = $this->connection->executeStatement($query, (array) $data);
        if (!$result) {
            return null;
        }
        $dataArray = (array) $data;
        $dataArray['id'] = (int) $this->connection->getConnection()->lastInsertId();
        return new Product(
            (int) $dataArray['id'],
            $dataArray['nombre'],
            $dataArray['descripcion'],
            $dataArray['precio'],
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
        );
    }
    public function update(int $id, ProductCreateRequestDto $data): bool
    {
        $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id";
        $result = $this->connection->executeStatement($query, [
            'nombre' => $data->nombre,
            'descripcion' => $data->descripcion,
            'precio' => $data->precio,
            'id' => $id
        ]);
        return $result > 0;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM productos WHERE id = :id";
        $result = $this->connection->executeStatement($query, ['id' => $id]);
        return $result > 0;
    }
}