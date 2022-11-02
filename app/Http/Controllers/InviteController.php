<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckOnlyEmailRequest;
use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\InviteResource;
use App\Models\InviteToken;

class InviteController extends Controller
{
    public function listInvitedUsers(): JsonResponse
    {
        $totaluser = InviteToken::count();
        $users = InviteService::invitedList();
        
        return response()->json([
            'total' => $totaluser,
            'invitedUsers' => InviteResource::collection($users),
        ],200);
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

    public function revoke($id): JsonResponse
    {
        $status = InviteService::revokeInvite($id);

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
