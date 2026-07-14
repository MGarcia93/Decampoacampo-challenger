<?php
namespace App\Shared\Exceptions;
class NotFoundException extends HttpException
{
    public function __construct(string $message = 'No se encontró el recurso', ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}