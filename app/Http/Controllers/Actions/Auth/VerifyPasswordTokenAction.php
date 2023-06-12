<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateResetTokenRequest;
use Illuminate\Http\JsonResponse;

class VerifyPasswordTokenAction extends Controller
{
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
