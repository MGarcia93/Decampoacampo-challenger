<?php

declare(strict_types=1);

namespace App\Shared\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private static ?self $instance = null;

    private PDO $connection;

    private function __construct()
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                getenv('DB_HOST') ?: 'mysql',
                getenv('DB_PORT') ?: '3306',
                getenv('DB_DATABASE') ?: 'challenge'
            );

            $this->connection = new PDO(
                $dsn,
                getenv('DB_USERNAME') ?: 'challenge',
                getenv('DB_PASSWORD') ?: '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Could not connect to the database.',
                previous: $exception
            );
        }
    }


    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

   public function executeQuery(
    string $query,
    array $parameters = []
): array {
    $statement = $this->connection->prepare($query);
    $statement->execute($parameters);

    return $statement->fetchAll();
}

public function executeStatement(
    string $query,
    array $parameters = []
): int {
    $statement = $this->connection->prepare($query);
    $statement->execute($parameters);

    return $statement->rowCount();
}
}