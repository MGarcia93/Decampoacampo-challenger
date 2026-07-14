<?php

declare(strict_types=1);

namespace App\Shared\Http;

final readonly class Response
{
    private function __construct(
        public string $content,
        public int $statusCode,
        public string $contentType,
    ) {
    }

    public static function json(
        array $data,
        int $statusCode = 200
    ): self {
        return new self(
            content: json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            ),
            statusCode: $statusCode,
            contentType: 'application/json; charset=utf-8'
        );
    }

    public static function text(
        string $text,
        int $statusCode = 200
    ): self {
        return new self(
            content: $text,
            statusCode: $statusCode,
            contentType: 'text/plain; charset=utf-8'
        );
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header("Content-Type: {$this->contentType}");

        echo $this->content;
    }
}