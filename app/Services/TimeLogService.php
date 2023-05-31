<?php

namespace App\Services;

use App\Models\TimeLog;
use App\Models\User;

class TimeLogService
{
    protected User $user;

    /**
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public static function addTimeLog(array $validatedAddLog): object
    {
        return TimeLog::create($validatedAddLog);
    }

    public static function viewTotalTimeLogs($id)
    {
        return User::find($id)->timeLogs()->count();
    }


    /**
     * Undocumented function
     *
     * @param integer $id
     * @param integer $size
     * @return void
     */
    public function viewPaginateTimeLogs(int $id, int $size): object
    {
        return $this->user->find($id)->timeLogs()->latest()->paginate($size);
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
