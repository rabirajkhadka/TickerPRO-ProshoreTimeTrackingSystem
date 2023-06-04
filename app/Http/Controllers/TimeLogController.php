<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTimeLogRequest;
use App\Http\Requests\EditTimeLogRequest;
use App\Services\ProjectService;
use App\Services\TimeLogService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TimeLogResource;
use App\Models\Project;
use App\Models\TimeLog;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


class TimeLogController extends Controller
{
    use HttpResponses;
    protected TimeLogService $timeLogService;

    /**
     * @param TimeLogService $timeLogService
     */
    public function __construct(TimeLogService $timeLogService)
    {
        $this->timeLogService = $timeLogService;
    }


    public function addActivity(AddTimeLogRequest $request): JsonResponse
    {
        if (!ProjectService::checkProjectIdExists($request['project_id']) || !UserService::checkUserIdExists($request['user_id'])) {
            return response()->json([
                'message' => 'Project Id or User Id does not exist'
            ], 400);
        }
        $validatedAddLog = $request->validated();
        $log = TimeLogService::addTimeLog($validatedAddLog);
        if (!is_object($log)) {
            return response()->json([
                'message' => 'Could not create a time log'
            ], 400);
        }
        return response()->json([
            'message' => 'Time log created successfully',
            'log' => TimeLogResource::make($log)
        ]);
    }


    /**
     * @param integer $id
     * @throws ModelNotFoundException
     * @throws Exception
     * @return JsonResponse
     */
    public function viewLogs(int $id): JsonResponse
    {
        try {
            $totals = $this->timeLogService->viewTotalTimeLogs($id);
            $logs = $this->timeLogService->viewPaginateTimeLogs($id);

            return empty($totals) ? $this->errorResponse([], 'No logs found', Response::HTTP_NOT_FOUND) :
                $this->successResponse([
                    'total' => $totals,
                    'logs' => TimeLogResource::collection($logs)
                ], 'Logs found');
        } catch (ModelNotFoundException $modelNotFoundException) {

            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], 'User does not exist', Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }

    public function editActivity(EditTimeLogRequest $request, $id): JsonResponse
    {
        if (!ProjectService::checkProjectIdExists($request['project_id']) || !UserService::checkUserIdExists($request['user_id'])) {
            return response()->json([
                'message' => 'Project Id or User Id does not exist'
            ], 400);
        }
        $validatedEditLog = $request->validated();
        $status = TimeLogService::editTimeLog($validatedEditLog, $id);
        if (!$status) {
            return response()->json([
                'message' => 'Time log with this id does not exist'
            ], 400);
        }
        return response()->json([
            'message' => 'Time log edited successfully'
        ]);
    }

    public function removeActivity($id): JsonResponse
    {
        $status = TimeLogService::removeLog($id);
        if (!$status) {
            return response()->json([
                'message' => 'Time log with this id does not exist'
            ], 400);
        }
        return response()->json([
            'message' => 'Time log deleted successfully'
        ]);
    }

    
    public function generateReport(Request $request)
    {
        $user = User::find($request->user_id);
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
    
        $report = [];
        $overall_total_time = 0;
    
        // TimeLogs of that particular user only
        $timeLogs = $user->timeLogs()
            ->whereBetween('start_date', [$start_date, $end_date])
            ->get();
        // dd($timeLogs->toArray());
        
        //
        $report = [];
        $overall_total_time = 0;
    
        // TimeLogs of that particular user only
        $timeLogs = $user->timeLogs()
            ->whereBetween('start_date', [$start_date, $end_date])
            ->get();
        // dd($timeLogs->toArray());
        
        // get project id worked on by that user only
        $projectIds = $timeLogs->pluck('project_id')->unique();
    
        foreach ($projectIds as $projectId) {
            $project = Project::find($projectId);
            $project_total_time = 0;
    
            $activities = $timeLogs->where('project_id', $projectId);
    
            foreach ($activities as $activity) {
                $startDateTime = Carbon::parse($activity->start_date . ' ' . $activity->started_time);
                $endDateTime = Carbon::parse($activity->end_date . ' ' . $activity->ended_time);
                $activity_total_time = $endDateTime->diffInHours($startDateTime);

                $formattedDate = Carbon::parse($activity->start_date)->format('M j');
    
                $report[] = [
                    'activity' => $activity->activity_name,
                    'date' => $formattedDate,
                    'project' => $project->project_name,
                    'total_hours' => $activity_total_time,
                ];
    
                $project_total_time += $activity_total_time;
                $overall_total_time += $activity_total_time;
            }
    
            $report[] = [
                'activity' => 'Total',
                'date' => '',
                'project' => $project->project_name,
                'total_hours' => $project_total_time,
            ];
        }
    
        $report[] = [
            'activity' => '',
            'date' => '',
            'project' => 'Overall Total Time',
            'total_hours' => $overall_total_time,
        ];
    
        // return view('reports.index', compact('report', 'user', 'start_date', 'end_date'));

        // Generate HTML for the report view
        $html = view('reports.index', compact('report', 'user', 'start_date', 'end_date'))->render();
        
        // Generate the PDF using Dompdf
        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Generate a unique filename for the PDF
        $filename = 'report_' . Carbon::now()->format('YmdHis') . '.pdf';

        // Save the PDF file
        $dompdf->stream($filename);
    }
}
