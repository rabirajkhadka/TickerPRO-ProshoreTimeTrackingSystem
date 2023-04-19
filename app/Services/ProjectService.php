<?php

namespace App\Services;

use App\Models\Project;
use App\Models\UserProject;
use Exception;

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

        if (! is_object($log)) {
            return false;
        }

        return true;
    }

    public static function updateProject($validatedEditProject, $id)
    {
        $project = Project::where('id', $id)->firstOrFail();
        $project->update($validatedEditProject);

        return $project;
    }

    public static function checkProjectIdExists($id)
    {
        $user = Project::where('id', $id)->first();

        if (! $user) {
            return false;
        }

        return true;
    }

    public function removeProject(int $id): void
    {
        $project = Project::where('id', $id)->first();
        if (! $project) {
            throw new Exception('Project With this Id doesnt exist', );
        }
        $project->delete();
    }
}
