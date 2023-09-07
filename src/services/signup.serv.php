<?php
include __DIR__ . "/../db/UserDao.php";

$userDao = new UserDao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Checks if email or password is empty
    if (empty($email) || empty($password)) {
        echo "Please fill out both fields";
        return http_response_code(400);
    } else {
        $pword = password_hash($password, PASSWORD_ARGON2ID);
        $user = new users($email, $pword, "user");

        // Checks if the user already exists
        if ($userDao->getUser($user->getEmail())) {
            echo "Something went wrong";
            return http_response_code(409);
        } else {
            if ($userDao->createUser($user)) {
                return http_response_code(204);
            } else {
                //TODO: ERROR HANDLING
                echo "Something went wrong on the server, try again later";
                return http_response_code(400);
            }
        }
    }
}
