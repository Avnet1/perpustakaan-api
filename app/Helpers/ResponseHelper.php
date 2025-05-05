<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{

    public static function sendResponseJson(bool $success, int $code, string $message, mixed $data = null): JsonResponse
    {
        if ($success == true) {
            return response()->json([
                'status' => $success,
                'message' => $message,
                'data' => $data
            ], $code);
        } else {
            return response()->json([
                'status' => $success,
                'message' => $message,
                'error' => $data
            ], $code);
        }
    }
}
