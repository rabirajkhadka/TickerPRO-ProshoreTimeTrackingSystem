<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function listInvitedUsers(): JsonResponse
    {
        return response()->json([
            'message' => 'Its working'
        ]);
    }

    public function resendInvite()
    {
    }

    public function revokeInvite()
    {
    }
}
