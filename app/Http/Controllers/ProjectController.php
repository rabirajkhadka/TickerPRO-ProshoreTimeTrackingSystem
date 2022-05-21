<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\UserProject;
use Mockery\Exception;
use App\Services\ProjectService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function addActivity(ProjectRequest $request): JsonResponse
    {  
        $result = ProjectService::addProject($request);
        if (!$result) {
            return response()->json([
                'message' => 'Could not create a project'
            ], 400);
        }
        return response()->json([
            'message' => 'Project created successfully'
        ]);
    }

    public function updateActivity(ProjectRequest $request): JsonResponse
    {
        $result = ProjectService::updateProject($request);
        if (!$result) {
            return response()->json([
                'message' => 'Could not update the project'
            ], 400);
        }
        return response()->json([
            'message' => 'Project updated successfully'
        ]);
    }
}
