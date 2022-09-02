<?php

namespace App\Services;

use App\Models\TimeLog;
use App\Models\User;
use App\Http\Requests\AddTimeLogRequest;
use App\Http\Requests\EditTimeLogRequest;

class TimeLogService
{
    public static function addTimeLog(AddTimeLogRequest $request): object
    {
        $validated = $request->validated();
        return TimeLog::create($validated);
    }

    public static function viewTimeLogs(int $id, int $size)
    {
        return User::find($id)->timeLogs()->paginate($size);
    }

    public static function editTimeLog(EditTimeLogRequest $request): bool
    {
       // after validation find the time log
        $log = TimeLog::where('id', $request->id)->first();
        if (!$log) return false;

        // if time log exists then update the details
        $log->forceFill($request->validated());
        $log->save();

        return true;
    }

    public static function removeLog($request): bool
    {
        $log = TimeLog::where('id', $request->id)->first();
        if (!$log) return false;
        //if log exists then delete
        $log->delete();
        return true;
    }

}
