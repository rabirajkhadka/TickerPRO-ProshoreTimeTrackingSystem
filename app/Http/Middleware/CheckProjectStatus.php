<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Project;

class CheckProjectStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $project = Project::where('id', $request->id)->first();
        if(empty($project)){
            return response()->json([
                'message' => 'Project does not exits'
            ],403);
        }
        if(!$project->status) {
            return response()->json([
                'message' => 'The project is disabled'
            ],403);
        }
        return $next($request);
    }
}
