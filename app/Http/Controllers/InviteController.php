<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOnlyEmailRequest;
use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function listInvitedUsers(): JsonResponse
    {
        $users = InviteService::invitedList();
        return response()->json([
            'total' => count($users),
            'invitedUsers' => $users
        ]);
    }

    public function reInvite(CheckOnlyEmailRequest $request, InviteService $inviteService): JsonResponse
    {
        $validated = $request->safe()->only(['email']);
        $status = $inviteService->resendInvite($validated['email']);

        if (!$status) {
            return response()->json([
                'message' => 'User does not exist in our database'
            ], 500);
        }

        return response()->json([
            'message' => 'User re-invited successfully'
        ]);

    }

    public function revoke(Request $request): JsonResponse
    {
        $status = InviteService::revokeInvite($request);

        if (!$status) {
            return response()->json([
                'message' => 'Cannot revoke invite. User does not exist in our database'
            ], 500);
        }
        return response()->json([
            'message' => 'User invite revoked successfully'
        ]);
    }
}
