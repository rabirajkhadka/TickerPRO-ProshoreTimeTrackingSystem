<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTimeLogRequest;
use App\Http\Requests\EditTimeLogRequest;
use App\Services\TimeLogService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TimeLogController extends Controller
{
    public function addActivity(AddTimeLogRequest $request): JsonResponse
    {
        $status = TimeLogService::addTimeLog($request);
        if (!$status) {
            return response()->json([
                'message' => 'Could not create a time log'
            ], 400);
        }
        return response()->json([
            'message' => 'Time log created successfully'
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
        $logs = TimeLogService::viewTimeLogs($request->id);
        if (empty($logs)) {
            return response()->json([
                'message' => 'No logs found',
            ]);
        }

        return response()->json([
            'message' => 'Logs found',
            'total' => count($logs),
            'logs' => $logs
        ]);
    }

    public function editActivity(EditTimeLogRequest $request): JsonResponse
    {
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
}
