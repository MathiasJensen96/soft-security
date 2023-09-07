<?php
require_once "ErrorResponse.php";

use error_handling\ErrorResponse;

function exceptionHandler(Throwable $e): void
{
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    ErrorResponse::makeErrorResponse(500, "Internal Server Error");
    exit;
}