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
    protected $signature = 'send:reminderEmails';

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
                        Carbon::now()->subWeek(),Carbon::now()
                    ])
                    ->whereBetween('end_date', [
                        Carbon::now()->subWeek(),Carbon::now()
                    ]);
            }
        ])->get();
        $hours = (new ReportService())->getUsersReportDetails($users);
        foreach ($hours as $user){
            if (Arr::get($user, 'total_time') < 40)
            {
                User::find($user['user_id'])->notify(new WeeklyTargetReminder());
            }
        }
        $this->info('Emails Sent!');
    }
}
