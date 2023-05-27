<?php

namespace App\Exceptions;
    
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
Use TypeError;
use Illuminate\Support\Str;
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

    public function render($request, Throwable $exception)
{
    if ($exception instanceof TypeError) {
        $errorMessage = $exception->getMessage(); // Get the original error message

        $parameterName = Str::between($errorMessage, 'App\Http\Controllers\ClientController::update(): Argument #2 ' ,' must be of type', ); // Extract the parameter name
        $parameterName = Str::replaceFirst('$', '', $parameterName);// Remove the '$' symbol if present
        $parameterName = Str::replaceFirst('(', '', $parameterName);// Remove the '(' symbol if present
        $parameterName = Str::replaceFirst(')', '', $parameterName); // Remove the ')' symbol if present

        $customErrorMessage = "Invalid type provided for parameter {$parameterName}";

        return $this->errorResponse([], "$customErrorMessage"); //use api error response trait
    } 

    return parent::render($request, $exception);
}
}
