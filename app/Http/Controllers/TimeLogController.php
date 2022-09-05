<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTimeLogRequest;
use App\Http\Requests\EditTimeLogRequest;
use App\Services\ProjectService;
use App\Services\TimeLogService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\TimeLogResource;

class TimeLogController extends Controller
{
    public function addActivity(AddTimeLogRequest $request): JsonResponse
    {
        if (!ProjectService::checkProjectIdExists($request['project_id']) || !UserService::checkUserIdExists($request['user_id'])) {
            return response()->json([
                'message' => 'Project Id or User Id does not exist'
            ], 400);
        }
        $log = TimeLogService::addTimeLog($request);
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

    public function viewLogs(Request $request): JsonResponse
    {
        //validate if user id passed is actually do exist
        $status = UserService::checkUserIdExists($request->id);
        if (!$status) {
            return response()->json([
                'message' => 'User does not exist'
            ], 400);
        }
        // if user exists then view their logs

        $size = $request->size;
        $totals = TimeLogService::viewTotalTimeLogs($request->id);
        $logs = TimeLogService::viewTimeLogs(size:(int)$size,id:(int)$request->id);
        if (empty($logs)) {
            return response()->json([
                'message' => 'No logs found',
            ]);
        }

        return response()->json([
            'message' => 'Logs found',
            'total' => $totals,
            'logs' => TimeLogResource::collection($logs)
        ]);
    }

    public function editActivity(EditTimeLogRequest $request): JsonResponse
    {
        if (!ProjectService::checkProjectIdExists($request['project_id']) || !UserService::checkUserIdExists($request['user_id'])) {
            return response()->json([
                'message' => 'Project Id or User Id does not exist'
            ], 400);
        }
        $status = TimeLogService::editTimeLog($request);
        if (!$status) {
            return response()->json([
                'message' => 'Time log with this id does not exist'
            ], 400);
        }
        return response()->json([
            'message' => 'Time log edited successfully'
        ]);

    }

    public function removeActivity(Request $request): JsonResponse
    {
        $status = TimeLogService::removeLog($request);
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
