<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateInviteTokenRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyInviteTokenAction extends Controller
{
    use HttpResponses;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(ValidateInviteTokenRequest $request): JsonResponse
    {
        return $this->successResponse([], 'Token verified');
    }
}
