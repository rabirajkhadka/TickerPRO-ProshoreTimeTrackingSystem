<?php

namespace App\Services;

use App\Models\TimeLog;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class ReportService
{  
    public function totalhours($validated)
    {
    $reports = $users->map(function ($user) use ($validated) {
        $userTotalTime = $user->timelogs->sum(function ($timelog) {
            $startDateTime = Carbon::parse($timelog->start_date . ' ' . $timelog->started_time);
            $endDateTime = Carbon::parse($timelog->end_date . ' ' . $timelog->ended_time);
            return $endDateTime->diffInMinutes($startDateTime);
        });
        $activities = $user->timelogs->map(function ($timelog) use ($validated) {
            $startDateTime = Carbon::parse($timelog->start_date . ' ' . $timelog->started_time);
            $endDateTime = Carbon::parse($timelog->end_date . ' ' . $timelog->ended_time);
            $totalTime = $endDateTime->diffInMinutes($startDateTime);
            $activity = [
                'activity' => $timelog->activity_name,
                'total_time' =>  intdiv($totalTime, 60) . 'hrs ' . ' ' . ($totalTime % 60) . 'min',
                'project' => $timelog->project->project_name
            ];
            if ($validated['project_id'] === null)
                $activity += ['project' => $timelog->project->project_name];
            return $activity;
        });
        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            // 'project' => $validated['project_id'] !== null ?
            //     $user->timelogs->pluck('project.project_name')->first() :
            //     $user->timelogs->pluck('project.project_name')->unique(),
            'client' => $user->timelogs->pluck('project.client.client_name')->first(),
            'total_time' => intdiv($userTotalTime, 60) . 'hrs ' . ' ' . ($userTotalTime % 60) . 'min',
            'activities' => $activities,
        ];
    });
    // dd($reports);
    return ($reports->all());
    }
}