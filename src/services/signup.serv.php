<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use error_handling\ErrorResponse;
use security\InputValidator;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

$validator = new InputValidator();
$validator->credentials($_POST);

$email = $_POST["email"];
$password = $_POST["password"];

$userDao = new UserDao();

$hashed = password_hash($password, PASSWORD_ARGON2ID);
$user = new users($email, $hashed, "user");

// Checks if the user already exists
if ($userDao->getUserByEmail($user->getEmail())) {
    ErrorResponse::makeErrorResponse(409, "A user with that email already exists");
    exit;
} else {
    if ($userDao->createUser($user)) {
        http_response_code(201);
        exit;
    } else {
        //TODO: ERROR HANDLING
        ErrorResponse::makeErrorResponse(500, "Something went wrong");
        exit;
    }
}
