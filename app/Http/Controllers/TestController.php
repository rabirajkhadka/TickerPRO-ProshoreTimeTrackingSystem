<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Requests\TimelogReportRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Notifications\MinHours;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
    protected ReportService $reportService;

    /**
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // // $validated = $request->validated();
        // // $user = auth()->user();
        // // if (!$this->userService->hasRoleAdmin($user)) {
        // //     $validated['user_ids'] = [auth()->id()];
        // // }
        // $users = User::with('timelogs')->get();
        // // dd($users);
        // $hours = $this->reportService->getUserHours($users);
        // // dd($hours);
        // foreach ($hours as $user) {
        //     // dd($user);
        //     if ($user['total_time'] < 40) {
        //         $result[] = $user['total_time'];
        //     }else       
        //     $result[] = 'nice';
        // }
        // return response()->json($result);

        // $users = User::all();
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
        $hours = $this->reportService->getUsersReportDetails($users);
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
