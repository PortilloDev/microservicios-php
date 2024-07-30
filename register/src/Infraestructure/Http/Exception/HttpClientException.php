<?php

namespace App\Infraestructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
class HttpClientException extends HttpException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($code, $message, $previous);
    }
}