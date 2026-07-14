<?php
namespace App\Shared\Exceptions;
class ValidateException extends HttpException
{
    public function __construct(string $message = 'Validación fallida', ?\Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}