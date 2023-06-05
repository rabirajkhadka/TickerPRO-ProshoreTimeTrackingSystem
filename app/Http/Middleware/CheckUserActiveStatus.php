<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserActiveStatus
{
    use HttpResponses; // Import the HttpResponses trait

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user->activeStatus) {
            return $this->errorResponse('Your account is disabled. Please contact the administrator.', 403);
        }

        return $next($request);
    }

    /**
     * Generate an error response.
     *
     * @param  string  $message
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    protected function errorResponse(string $message, int $code): Response
    {
        return response($message, $code);
    }
}
