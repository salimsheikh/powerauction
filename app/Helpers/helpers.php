<?php

if (!function_exists('jsonResponse')) {
    /**
     * Generate a standardized JSON error response.
     *
     * @param string $message
     * @param array|null $errors
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonResponse($success, string $message, array $errors = null, int $statusCode = 500)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
