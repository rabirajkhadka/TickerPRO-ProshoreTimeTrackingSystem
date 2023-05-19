<?php

namespace App\Http\Controllers\Actions\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberInviteRequest;
use App\Services\InviteService;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InviteOthersAction extends Controller
{
    use HttpResponses;

    protected InviteService $inviteService;

    /**
     *
     * @param UserService $userService
     * @param InviteService $inviteService
     */

    public function __construct(InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @param MemberInviteRequest $request
     * @param InviteService $inviteService
     * @throws QueryException
     * @throws Exception
     * @return JsonResponse
     */

    public function __invoke(MemberInviteRequest $request): JsonResponse
    {
        $validatedInputs =  $request->validated();
        try {
            $token = $this->inviteService->generateToken();
            $this->inviteService->invite($validatedInputs, $token);
            if (config('app.env') === 'local' || config('app.env') === "development") {
                return $this->successResponse(['token' => $token], 'User invited successfully');
            }
            return $this->successResponse([], 'User invited successfully');
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'User could not be invited');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }
}
