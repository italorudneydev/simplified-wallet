<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Custom exception handling
        $this->renderable(function (ServiceUnavailableException $e, $request) {
            return response()->json(['error' => $e->getMessage()], 503);
        });

        $this->renderable(function (ConflictException $e, $request) {
            return response()->json(['error' => $e->getMessage()], 409);
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            return response()->json(['error' => $e->getMessage()], 401);
        });
    }
}
