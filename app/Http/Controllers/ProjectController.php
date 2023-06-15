<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use \Exception;
use App\Services\ProjectService;
use App\Http\Requests\{ProjectRequest, EditProjectRequest, ViewProjectRequest};
use App\Http\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $retrieveOption = $request->query('all', Project::STATUS_FALSE);
            $projects = $this->projectService->listProjects($user, $retrieveOption);
            $data = [ProjectResource::collection($projects)];
            return $this->successResponse($data, 'Projects retrieved successfully', Response::HTTP_OK);
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "Project not found", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong.");
        }
    }

    public function addActivity(ProjectRequest $request): JsonResponse
    {
        $validatedAddProject = $request->validated();
        $addProjectData = ProjectService::addProject($validatedAddProject);
        if (!$addProjectData) {
            return response()->json([
                'message' => 'Could not create a project'
            ], 400);
        }
        return response()->json([
            'message' => 'Project created successfully'
        ]);
    }

    public function updateActivity(EditProjectRequest $request, $id)
    {
        $validatedEditProject = $request->validated();
        try {
            $updateProjectData = ProjectService::updateProject($validatedEditProject, $id);
            return response()->json([
                "message" => "Project Updated Successfully",
                "project" => $updateProjectData
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Project with this Id doesnt Exists",
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateProjectStatus(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        try {
            if (!$project->status) {
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
            if (!$project->billable) {
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

    public function deleteProject(int $id): JsonResponse
    {
        try {
            $this->projectService->removeProject($id);
            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
