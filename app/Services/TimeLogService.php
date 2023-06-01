<?php

namespace App\Services;

use App\Models\TimeLog;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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


    /**
     * @param integer $id
     * @throws ModelNotFoundException
     * @throws Exception
     * @return int
     */
    public function viewTotalTimeLogs(int $id): int
    {
        try {
            return $this->user->findOrFail($id)->timeLogs()->count();
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (Exception) {
            throw new Exception();
        }
    }


    /**
     * @param integer $id
     * @throws ModelNotFoundException
     * @throws Exception
     * @return object
     */
    public function viewPaginateTimeLogs(int $id): object
    {
        try {
            return $this->user->findOrFail($id)->timeLogs()->latest()->paginate();
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (Exception) {
            throw new Exception();
        }
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
