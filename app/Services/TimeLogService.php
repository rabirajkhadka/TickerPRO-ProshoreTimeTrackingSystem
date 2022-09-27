<?php

namespace App\Services;

use App\Models\TimeLog;
use App\Models\User;

class TimeLogService
{
    public static function addTimeLog(array $validatedAddLog): object
    {
        return TimeLog::create($validatedAddLog);
    }

    public static function viewTotalTimeLogs($id)
    {
        return User::find($id)->timeLogs()->count();
    }

    public static function viewPaginateTimeLogs(int $id, int $size)
    {
       return User::find($id)->timeLogs()->paginate($size);

        
    }

    public static function editTimeLog(array $validatedEditLog, $id): bool
    {
       // after validation find the time log
       $log = TimeLog::where('id', $id)->first();
        if (!$log) return false;

        // if time log exists then update the details
        $log->update($validatedEditLog);
        
        $log->save();

        return true;
    }

    public static function removeLog($id): bool
    {
        $log = TimeLog::where('id', $id)->first();
        if (!$log) return false;
        //if log exists then delete
        $log->delete();
        return true;
    }

}
