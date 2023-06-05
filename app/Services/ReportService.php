<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;

class ReportService
{
    protected User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @param array $validated
     * @return void
     */
    public function getUsersReport(array $validated)
    {
        $users = $this->user->whereIn('id', Arr::get($validated, 'user_id'))
            ->with(['timelogs' => function ($query) use ($validated) {
                $timelogQuery = $query->whereBetween('start_date', [Arr::get($validated, 'start_date'), Arr::get($validated, 'end_date')])
                    ->whereBetween('end_date', [Arr::get($validated, 'start_date'), Arr::get($validated, 'end_date')])
                    ->where('billable', 1)
                    ->whereHas('project', function ($query) {
                        $query->where('billable', 1);
                    })
                    ->with('project.client');

                if ($validated['project_id'] !== null) {
                    $timelogQuery->where('project_id', Arr::get($validated, 'project_id'));
                }
            }])->get();

        $report = $this->getUsersReportDetails($validated, $users);
        return $report;
    }


    /**
     * @param array $validated
     * @param object $users
     * @return void
     */
    public function getUsersReportDetails(array $validated, object $users)
    {
        //
    }


    /**
     * @param array $validated
     * @param object $user
     * @return void
     */
    public function getUserActivity(array $validated, object $user)
    {
        //
    }
}
