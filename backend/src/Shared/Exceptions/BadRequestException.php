<?php
namespace App\Shared\Exceptions;
class BadRequestException extends HttpException
{
    public function __construct(string $message = 'Solicitud incorrecta', ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}