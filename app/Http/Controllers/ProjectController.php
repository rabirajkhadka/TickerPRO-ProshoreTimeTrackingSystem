<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use \Exception;
use App\Services\ProjectService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProjectResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
   
    public function __construct(protected ProjectService $projectService)
    {

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

    public function updateActivity(ProjectRequest $request,$id)
    {
        $validatedEditProject = $request->validated();
        try {
            $updateProjectData = ProjectService::updateProject($validatedEditProject, $id);
            return response()->json([
                "message"=>"Project Updated Successfully",
                "project"=> $updateProjectData
            ],Response::HTTP_OK);   
        }
        catch(ModelNotFoundException $e){
            return response()->json([
                'message' => "Project with this Id doesnt Exists",
            ], Response::HTTP_BAD_REQUEST);
        } 
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateProjectStatus(Request $request)
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

    public function viewAllProjects(Request $request)
    {
        $search = $request['search'] ?? "";
        if($search != ""){
            $projects= Project::where('project_name','LIKE',"%$search%")->get();
        }
        else{
        $projects = Project::all();
        }
        
        return response()->json([
            'total' => count($projects),
            'projects' => ProjectResource::collection($projects)
        ], 200);
    }

    public function deleteProject(int $id): JsonResponse
    {
        try {
            $this->projectService->removeProject($id);
            return response()->json([
            ],Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ],Response::HTTP_BAD_REQUEST);
        }
    }
}
