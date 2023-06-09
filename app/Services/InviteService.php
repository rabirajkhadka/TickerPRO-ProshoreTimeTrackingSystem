<?php

namespace App\Services;

use App\Mail\InviteCreated;
use App\Mail\ReInvite;
use App\Models\InviteToken;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteService
{
    public function generateToken(): string
    {
        $random = Str::random(60);
        return $random;
    }

    public function invite($name, $email, $role_id, $user_id): bool
    {
        $token = $this->generateToken();
        $url = config('frontend.url') . '/register/' . $token . '?email=' . $email . '&name=' . urlencode($name);

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

    public static function invitedList(): Collection
    {
        return InviteToken::latest()->get();
    }

    public function resendInvite($email): bool
    {
        $user = InviteToken::where('email', $email)->first();
        if (!$user) return false;

        //if user exists then generate new token and email
        $token = $this->generateToken();
        $url = config('frontend.url') . '/register/' . $token . '?email=' . $email . '&name=' . urlencode($user->name);
        $user->forceFill([
            'token' => $token
        ]);
        $user->save();
        Mail::to($email)->send(new ReInvite($url));

        return true;
    }

    public static function revokeInvite($id): bool
    {
        $user = InviteToken::where('id', $id)->first();
        if (!$user) return false;

        //if users exists then delete their invite
        $user->delete();
        return true;
    }
}
