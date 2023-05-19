<?php

namespace App\Services;

use App\Mail\InviteCreated;
use App\Mail\ReInvite;
use App\Models\InviteToken;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteService
{
    protected InviteToken $inviteToken;

    /**
     * @param InviteToken $inviteToken
     */

    public function __construct(InviteToken $inviteToken)
    {
        $this->inviteToken = $inviteToken;
    }

    /**
     * @return string
     */

    public static function generateToken(): string
    {
        $random = Str::random(45);
        return $random . uniqid();
    }

    /**
     *
     * @param array $credentials
     * @return void
     */

    public function invite(array $credentials, string $token): void
    {
        $url = config('frontend.url') . '/register' . '?token=' . $token .
            '&email=' .  $credentials['email'] . '&name=' . urlencode($credentials['name']);

        $credentials['token'] = $token;
        $credentials['tokenExpires'] = Carbon::now()->addDays(5);
        $credentials['invitedUserId'] = $credentials['user_id'];

        try {
            $this->inviteToken->create($credentials);
            Mail::to(Arr::get($credentials, 'email'))->send(new InviteCreated($url));
        } catch (QueryException) {
            throw new QueryException();
        } catch (Exception) {
            throw new Exception();
        }
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
