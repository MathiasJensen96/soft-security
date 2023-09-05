<?php
include __DIR__ . "/../db/UserDao.php";

$userDao = new UserDao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    if (empty($email) || empty($password)) {
        return http_response_code(400);
    } else {
        $pword = password_hash($password, PASSWORD_DEFAULT);
        $user = new users($email, $pword, "user");

        if ($userDao->getUser($user->getEmail())) {
            echo "A user with that email already exists";
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