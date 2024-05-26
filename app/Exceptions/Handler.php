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
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    // public function render($request, Throwable $exception)
    // {
    //     // if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
    //     //     return response()->json(['User have not permission for this page access.']);
    //     // }
    //     return parent::render($request, $exception);
    // }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        // if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
        //     return response()->json(['User have not permission for this page access.']);
        // }
        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
