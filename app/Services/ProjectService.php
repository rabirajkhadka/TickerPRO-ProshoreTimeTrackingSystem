<?php

namespace App\Services;

use App\Models\Project;
use App\Models\UserProject;

class ProjectService
{
    public static function addProject(array $validatedAddProject): bool
    {
        $log = Project::create($validatedAddProject);

        $id = auth()->user()->id;
        $userproject = new UserProject;
        $userproject->user_id = $id;
        $userproject->project_id = $log->id;
        $userproject->save();

        if (!is_object($log)) return false;
        return true;
    }

    public static function updateProject($validatedEditProject, $id): bool
    {
        $project = Project::where('id', $id)->first();
        $project->forceFill($validatedEditProject);
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
