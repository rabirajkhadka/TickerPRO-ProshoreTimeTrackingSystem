<?php

namespace App\Jobs;

use App\Mail\WeeklyHourReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class WeeklyHourReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $totalHour, $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($totalHour, $user)
    {
        $this->totalHour = $totalHour;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new WeeklyHourReport($this->totalHour));
    }
}
