<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTimeLogRequest;
use App\Services\TimeLogService;
use Illuminate\Http\JsonResponse;


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
}
