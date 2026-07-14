<?php
namespace App\Products\Repositories;
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
    public function create(array $data): ?Product
    {
        $query = "INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)";
        $result = $this->connection->executeStatement($query, $data);
        if (!$result) {
            return null;
        }
        $data['id'] = (int) $this->connection->getConnection()->lastInsertId();
        return new Product(...$data);
    }
    public function update(int $id, array $data): bool
    {
        $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id";
        $data['id'] = $id;
        $result = $this->connection->executeStatement($query, $data);
        return $result > 0;
    }
    public function delete(int $id): bool
    {
        $query = "DELETE FROM productos WHERE id = :id";
        $result = $this->connection->executeStatement($query, ['id' => $id]);
        return $result > 0;
    }
}