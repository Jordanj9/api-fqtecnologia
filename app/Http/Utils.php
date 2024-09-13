<?php

namespace App\Http;

class Utils
{
    public static function responseJson(int $statusCode, string $message, $data = null, int $httpCode){
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }
}