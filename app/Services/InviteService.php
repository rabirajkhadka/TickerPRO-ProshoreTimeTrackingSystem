<?php

namespace App\Services;

use App\Mail\InviteCreated;
use App\Models\InviteToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteService
{
    public static function invite($name, $email, $role_id, $user_id): bool
    {
        // generate a token and save it in the database with the corresponding email and role id
        $random = Str::random(60);
        $time = Carbon::now();
        $token = $random . $time->toDateTimeLocalString();
        $url = env('frontend_url').'/register/'.$token.'?email='.$email;

        $user = InviteToken::create([
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'token' => $token,
            'tokenExpires' => Carbon::now()->addDays(5),
            'invitedUserId' => $user_id
        ]);
        if (!$user) return false;
        // send an email notifying that you are invited
        Mail::to($email)->send(new InviteCreated($url));
        return true;
    }
}

