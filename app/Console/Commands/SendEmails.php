<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\ReportService;
use App\Notifications\WeeklyTargetReminder;
use Illuminate\Support\Arr;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminders to Users about their total Weekly hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::with([
            'timelogs' => function ($query) {
                $query
                    ->whereBetween('start_date', [
                        Carbon::now()->subWeek(), Carbon::now()
                    ])
                    ->whereBetween('end_date', [
                        Carbon::now()->subWeek(), Carbon::now()
                    ]);
            }
        ])->get();
        foreach ($users as $user) {
            $userTotalTime = $user->timelogs->sum(function ($timelog) {
                $startDateTime = Carbon::parse($timelog->start_date . ' ' . $timelog->started_time);
                $endDateTime = Carbon::parse($timelog->end_date . ' ' . $timelog->ended_time);
                return $endDateTime->diffInMinutes($startDateTime);
            });
            if ($userTotalTime < 40) {
                $user->notify(new WeeklyTargetReminder($user->name));
            }
        }
        $this->info('Emails Sent!');
    }
}
