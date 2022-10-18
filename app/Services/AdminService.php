<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Response;

class AdminService
{
    public static function deleteUser($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
          return false;
        }

        return true;
        
    }
}
