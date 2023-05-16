<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait HttpResponses
{
    /**
     *
     * @param string|null $message
     * @param integer $status
     * @return JsonResponse
     */

    public function successResponse(array $data, string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }



    /**
     *
     * @param string|null $message
     * @param integer $status
     * @return JsonResponse
     */
    public function errorResponse(array $data, string $message = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}