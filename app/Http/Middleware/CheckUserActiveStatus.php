<?php

namespace App\Http\Middleware;

use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserActiveStatus
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user->activeStatus) {
            return $this->errorResponse([], "User currently disabled", Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
