<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTimeLogRequest;
use App\Http\Requests\EditTimeLogRequest;
use App\Services\ProjectService;
use App\Services\TimeLogService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TimeLogResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TimeLogController extends Controller
{
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
}
