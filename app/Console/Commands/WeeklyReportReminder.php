<?php

namespace App\Console\Commands;

use App\Jobs\WeeklyHourReportJob;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WeeklyReportReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Weekly Hour Report Schedule Running');
        $weekly_timelog_hours = TimeLog::whereBetween('start_date', [Carbon::now()->subDays(7), Carbon::now()])
            ->selectRaw('TIMEDIFF(ended_time,started_time) as total_time,user_id')
            ->get()
            ->groupBy('user_id');

        foreach ($weekly_timelog_hours as $key => $user_logs) {
            $user = User::find($key);

            if ($user) {
                $totalTime = $user_logs->sum(function ($timeLog) {
                    $totalTimeParts = explode(':', $timeLog->total_time);
                    $hours = (int)$totalTimeParts[0];
                    $minutes = (int)$totalTimeParts[1];
                    $seconds = (int)$totalTimeParts[2];
                    $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                    return $totalSeconds;
                });

                $totalHour = $totalTime / (3600);

                if ($totalHour < 40) {
                    $hours = floor($totalHour);
                    $minutes = round(fmod($totalHour, 1) * 60);
                    dispatch(new WeeklyHourReportJob($hours . " hours and " . $minutes . " minutes", $user));
                }
            }
        }
    }
}
