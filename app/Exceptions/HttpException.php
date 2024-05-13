<?php

namespace App\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected int $statusCode;

    public function __construct($statusCode, $message = null, $code = 0, Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
