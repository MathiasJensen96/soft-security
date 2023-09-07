<?php

namespace error_handling;

class ErrorResponse
{
    public function __construct(public int $code, public string $message)
    {
    }

    public static function makeErrorResponse(int $code, string $message): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(new ErrorResponse($code, $message));
    }
}