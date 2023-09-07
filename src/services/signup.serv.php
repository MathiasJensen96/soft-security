<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . "/../security/InputValidator.php";

use security\InputValidator;

$userDao = new UserDao();
$validator = new InputValidator();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // validates input
    if (empty($email) || empty($password)) {
        echo "Email and password must be filled out";
        return http_response_code(400);
    } else if (!$validator->email($email)) {
        echo "Invalid email";
        return http_response_code(400);
    } else if (!$validator->complexPassword($password)) {
        echo $validator::PASSWORD_RULES;
        return http_response_code(400);
    } else {
        $hashed = password_hash($password, PASSWORD_ARGON2ID);
        $user = new users($email, $hashed, "user");

        // Checks if the user already exists
        if ($userDao->getUser($user->getEmail())) {
            echo "A user with that email already exists";
            return http_response_code(409);
        } else {
            if ($userDao->createUser($user)) {
                return http_response_code(204);
            } else {
                //TODO: ERROR HANDLING
                echo "Something went wrong on the server, try again later";
                return http_response_code(500);
            }
        }
    }
}
