<?php
namespace App\Helpers;

function successResponse($data, $message = null, $code = 200)
{
    return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $data,
        'errors' => null,
    ], $code);
}

function errorResponse($message, $errors = null, $code = 500)
{
    return response()->json([
        'success' => false,
        'message' => $message,
        'data' => null,
        'errors' => $errors,
    ], $code);
}
