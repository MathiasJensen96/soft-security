<?php
require_once "ErrorResponse.php";

use error_handling\ErrorResponse;

function exceptionHandler(Throwable $e): void
{
    error_log(get_class($e) . ": " . $e->getMessage() . "\n" . $e->getTraceAsString());
    ErrorResponse::makeErrorResponse(500, "Internal Server Error");
    exit;
}