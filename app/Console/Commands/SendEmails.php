<?php

namespace App\Console\Commands;

use App\Notifications\MinHours;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Services\ReportService;
use App\Services\UserService;
use Illuminate\Support\Carbon;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:emails';

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
        // return [Carbon::now()->subWeek(),Carbon::now()];
        $hours = (new ReportService())->getUsersReportDetails($users);
        // return $hours;
        // return $users;
        foreach ($hours as $user){
            if ($user['total_time'] < 40){

                User::find($user['user_id'])->notify(new MinHours());
                // Notification::send($users , new MinHours());
            }
        }
        return 'mail sent';
    }
}
