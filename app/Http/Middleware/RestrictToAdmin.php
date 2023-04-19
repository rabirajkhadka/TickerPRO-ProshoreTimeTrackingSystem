<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictToAdmin
{
    /*
     * For now checks if the user contains any admin roles
     * Only admin roles are allowed to access next request
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user()->toArray();
        if (empty($user)) {
            return response()->json([
                'message' => 'Please login to proceed',
            ], 403);
        }
        $roles = User::find($user['id'])->roles;
        foreach ($roles as $role) {
            if ($role['role'] === 'admin') {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'You are not authorized to access this route',
        ], 403);
    }
}
