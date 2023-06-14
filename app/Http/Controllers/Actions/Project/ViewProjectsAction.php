<?php

namespace App\Http\Controllers\Actions\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViewProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewProjectsAction extends Controller
{
    protected ProjectService $projectService;

    /**
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(ViewProjectRequest $request): JsonResponse
    {
        try {
            $userRoles = Auth::user()->roles;
            $retrieveOption = $request->validated('all');
            $projects = $this->projectService->listProjects($userRoles, $retrieveOption);
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
}
