<?php

namespace App\Services;

use App\Models\Project;
use App\Models\UserProject;
use App\Http\Requests\ProjectRequest;


class ProjectService
{
    public static function addProject(ProjectRequest $request): bool
    {
        $log = Project::create($request->validated());

        $id = auth()->user()->id;
        $userproject = new UserProject;
        $userproject->user_id = $id;
        $userproject->project_id = $log->id;
        $userproject->save();

        if (!is_object($log)) return false;
        return true;
    }

    public static function updateProject(ProjectRequest $request): bool
    {
        $project = Project::where('id', $request->id)->first();

        $project->project_name = request('project_name');
        $project->client_id = request('client_id');
        $project->billable = request('billable');
        $project->status = request('status');

        $project->save();

        if (!is_object($project)) return false;
        return true;
    }

    public static function checkProjectIdExists($id)
    {
        $user = Project::where('id', $id)->first();

        if (!$user) return false;

        return true;
    }
}
