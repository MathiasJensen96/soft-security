<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . '/../security/AuthenticationManager.php';

use security\AuthenticationManager;

session_start();

if (empty($_POST['password'])) {
    return http_response_code(400);
}
$password = $_POST['password'];

$userDao = new UserDao();
$authenticationManager = new AuthenticationManager();

$user = $userDao->getUser($_POST['email']);
if ($user && password_verify($password, $user->getPassword())) { // Verifying password
    $authenticationManager->createSession();
    $_SESSION['role'] = $user->getRole();
    $_SESSION['email'] = $user->getEmail();

    if ($_SESSION['role'] === "admin") { // Maybe not the best practise to redirect here
        header("Location: /adminpage");
    }
} else {
    echo 'Incorrect username or password.';
    error_log("ip: |" . $_SERVER['REMOTE_ADDR'] . "| Attempted login to user: " . $user->getEmail());
}
