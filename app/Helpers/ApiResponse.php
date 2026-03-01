<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = [], $message = 'Success')
    {
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
        ], $data));
    }

    public static function error($message = 'An error occurred', $statusCode = 200)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }

    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }
}
