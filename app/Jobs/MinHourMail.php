<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ReportService;
use App\Models\User;

class MinHourMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected User $user;
    protected ReportService $reportService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, ReportService $reportService)
    {
        $this->user = $user;
        $this->reportService = $reportService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $start/enddate;
        $users = $this->user->with('timelogs')->get();
        // dd($users);
        $hours = $this->reportService->getUserHours($users);
        // dd($hours);
        foreach ($hours as $user) {
            // dd($user);
            if ($user['total_time'] < 40) {
                $result[] = $user['total_time'];
            }else       
            $result[] = 'nice';
        }
        return response()->json($result);
    }
}
