<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use TypeError;
use Throwable;
use App\Traits\HttpResponses;

class Handler extends ExceptionHandler
{
    use HttpResponses;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        });
    }



/**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TypeError) {
            $customErrorMessage = "Invalid type provided for parameter";
            return $this->errorResponse([], "$customErrorMessage"); //use api error response trait
        }

        // if  ($exception instanceof AuthenticationException && auth()->check() && !auth()->user()->isActive()) {
        //     $customErrorMessage = "You are currently disabled";
        //     return $this->errorResponse([], "$customErrorMessage");
        // }

        return parent::render($request, $exception);
    }

}
