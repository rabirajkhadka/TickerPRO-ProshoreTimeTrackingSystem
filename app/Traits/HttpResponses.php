<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    /**
     *
     * @param string|null $message
     * @param integer $status
     * @return JsonResponse
     */

    public function successResponse($data, string $message = null, int $code = 200): JsonResponse
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
    public function errorResponse($data, string $message = null, int $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
