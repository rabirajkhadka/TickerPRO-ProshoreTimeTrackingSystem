<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateResetTokenRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;

class VerifyPasswordTokenAction extends Controller
{
    use HttpResponses;

    /**
     *
     * @param ValidateResetTokenRequest $request
     * @return JsonResponse
     */
    public function __invoke(ValidateResetTokenRequest $request): JsonResponse
    {
        return $this->successResponse([], 'Token verified');
    }
}
