<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use error_handling\ErrorResponse;
use security\InputValidator;

$userDao = new UserDao();
$validator = new InputValidator();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // validates input
    if (empty($email) || empty($password)) {
        ErrorResponse::makeErrorResponse(400, "Email and password must be filled out");
    } else if (!$validator->email($email)) {
        ErrorResponse::makeErrorResponse(400, "Invalid email");
    } else if (!$validator->complexPassword($password)) {
        ErrorResponse::makeErrorResponse(400, $validator::PASSWORD_RULES);
    } else {
        $hashed = password_hash($password, PASSWORD_ARGON2ID);
        $user = new users($email, $hashed, "user");

        // Checks if the user already exists
        if ($userDao->getUser($user->getEmail())) {
            ErrorResponse::makeErrorResponse(409, "A user with that email already exists");
        } else {
            if ($userDao->createUser($user)) {
                http_response_code(201);
                exit;
            } else {
                //TODO: ERROR HANDLING
                ErrorResponse::makeErrorResponse(500, "Something went wrong");
            }
        }
    }
}
