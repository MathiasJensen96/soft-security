<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . "/../security/InputValidator.php";

use security\InputValidator;

$userDao = new UserDao();
$validator = new InputValidator();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    if (empty($email) || empty($password)
        || !$validator->email($email)
        || !$validator->complexPassword($password)) {
        return http_response_code(400);
    } else {
        $hashed = password_hash($password, PASSWORD_ARGON2ID);
        $user = new users($email, $hashed, "user");

        if ($userDao->getUser($user->getEmail())) {
            echo "Something went wrong";
            return http_response_code(409);
        } else {
            if ($userDao->createUser($user)) {
                return http_response_code(204);
            } else {
                //TODO: ERROR HANDLING
                echo "something went wrong";
                return http_response_code(400);
            }
        }
    }
}