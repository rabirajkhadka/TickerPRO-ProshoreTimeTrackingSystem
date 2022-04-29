<?php

namespace App\Http\Controllers;

use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function listInvitedUsers(): JsonResponse
    {
        $users = InviteService::invitedList();
        return response()->json([
            'invitedUsers' => $users
        ]);
    }

    public function resendInvite()
    {
    }

    public function revokeInvite()
    {
    }
}
