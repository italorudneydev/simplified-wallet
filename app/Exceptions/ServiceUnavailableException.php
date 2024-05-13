<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ServiceUnavailableException extends HttpException
{
    public function __construct($message = 'Service Temporarily Unavailable', $code = 0, Exception $previous = null)
    {
        parent::__construct(ResponseAlias::HTTP_SERVICE_UNAVAILABLE, $message, $code, $previous);
    }
}
