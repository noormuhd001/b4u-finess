<?php

namespace App\Traits;

trait JsonResponses
{
    public function successResponse($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public function errorResponse($message = 'Error', $status = 400, $errors = null)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
