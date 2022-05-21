<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\UserProject;

class ProjectService
{
    public static function addProject($request): bool
    {
        $validated = $request->validated();
        $log = Project::create(
            [
                'project_name' => $validated['project_name'],
                'client_name' => $validated['client_name'],
                'client_contact_number' => $validated['client_contact_number'],
                'client_email' => $validated['client_email'],
                'billable' => $validated['billable'],
                'status' => $validated['status'],
                'project_color_code' => $validated['project_color_code'],
            ]
        );

        $id = auth()->user()->id;
        $userproject = new UserProject;
        $userproject->user_id = $id;
        $userproject->project_id = $log->id;
        $userproject->save();

        if (!is_object($log)) return false;
        return true;
    }

    public static function updateProject($request): bool
    {
        $project = Project::where('id', $request->id)->first();

        $project->project_name = request('project_name');
        $project->client_name = request('client_name');
        $project->client_contact_number = request('client_contact_number');
        $project->client_email = request('client_email');
        $project->billable = request('billable');
        $project->status = request('status');
        $project->project_color_code = request('project_color_code');

        $project->save();

        if (!is_object($project)) return false;
        return true;
    }
}
