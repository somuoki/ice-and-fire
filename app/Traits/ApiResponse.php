<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponse{
    public function successResponse($data, $statusCode = Response::HTTP_OK){
        return response()->json(['data' => $data], $statusCode);
    }
    public function errorResponse($errorMessage, $statusCode){
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }
}
