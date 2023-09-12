<?php

require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../db/UserDao.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/InputValidator.php';

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('admin page', 'admin');

$inputValidator = new InputValidator();
$userDao = new UserDao;

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

if ($inputValidator->email($userEmail)) {
    $user = $userDao->getUser($userEmail);
    if (empty($user)) {
        error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find non-existent user: " . $userEmail);
        ErrorResponse::makeErrorResponse(404, "User not found with email: $userEmail");
        exit;
    }
    error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'get user' endpoint and found: " . json_encode($user) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
    header("Content-Type: application/json");

    echo json_encode($user, JSON_HEX_TAG | JSON_PRETTY_PRINT);
} else {
    error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find user with invalid email: " . $userEmail);
    ErrorResponse::makeErrorResponse(400, "Invalid email: $userEmail");
}