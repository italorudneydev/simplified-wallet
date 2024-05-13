<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UnauthorizedException extends HttpException
{
    public function __construct($message = 'Unauthorized Access', $code = 0, Exception $previous = null)
    {
        parent::__construct(ResponseAlias::HTTP_UNAUTHORIZED, $message, $code, $previous);
    }
}
