<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ConflictException extends HttpException
{
    public function __construct($message = 'Conflict Detected', $code = 0, Exception $previous = null)
    {
        parent::__construct(ResponseAlias::HTTP_CONFLICT, $message, $code, $previous);
    }
}
