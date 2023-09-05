<?php
include __DIR__ . "/../db/UserDao.php";

$userDao = new UserDao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $pword = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user = new users($email, $pword, "user");
    //print_r($user); //debug line
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