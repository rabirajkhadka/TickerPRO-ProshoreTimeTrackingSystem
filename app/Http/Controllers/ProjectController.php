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

    public function updateProjectStatus(ProjectRequest $request)
    {
        $project = Project::where('id', $request->id)->first();
        try {
            if(!$project->status) {
                $project->status = true;
            } else {
                $project->status = false;
            }
            $project->save();
            $result = [
                'status' => 200,
                'message' => 'Project status updated',
                'project' => $project,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    public function updateBillableStatus(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        try {
            if(!$project->billable) {
                $project->billable = true;
            } else {
                $project->billable = false;
            }
            $project->save();
            $result = [
                'status' => 200,
                'message' => 'Project billable status updated',
                'project' => $project,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    public function viewAllProjects()
    {
        $projects = Project::all();

        return response()->json([
            'total' => count($projects),
            'users' => $projects
        ], 200);

    }
}
