<?php

namespace App\Traits;

trait JsonResponseTrait
{
    
    public function successResponse($data, $message = null, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
   
    public function errorResponse($message, $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
