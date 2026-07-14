<?php
namespace App\Shared\Http;

use stdClass;
final class Request
{
    

    public function __construct(private ?stdClass $parameters=null)
    {
        $this->parameters = $this->parameters ?? new stdClass();
        $this->loadRequestParameters();
    }
 
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($path, '/');
    }

    public function loadRequestParameters(): void
    {
    

        if ($this->getMethod() === 'GET') {
            $this->mergeParameters($_GET);            
            return;
        }

        if (!in_array($this->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return;
        }

        if (!empty($_POST)) {
            $this->mergeParameters($_POST);
            return;
        }

        $body = $this->getBodyByContentType();
        $this->mergeParameters($body);
    }

    private function mergeParameters( array $data): void
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this->parameters, $key)) {
                $this->parameters->{$key} = $value;
            }
        }
    }

    private function getBodyByContentType(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $body = [];

        if (str_contains($contentType, 'application/json')) {
            $body = json_decode(file_get_contents('php://input'), true) ?? [];
        } elseif (str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str(file_get_contents('php://input'), $body);
        }

        return $body;
    }
    public function getParameter(string $name): mixed
    {
        
        return $this->parameters->{$name} ?? null;
    }

      public function getParameters(): stdClass
    {
        return $this->parameters;
    }
}