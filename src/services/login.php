<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../security/RateLimiter.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/getIp.php';

use error_handling\ErrorResponse;
use security\AuthenticationManager;
use security\RateLimiter;

session_start();

if (empty($_POST['password'])) {
    return http_response_code(400);
}
$password = $_POST['password'];

$rateLimiter = new RateLimiter(5, 60);
if (!$rateLimiter->isAllowed(getIp())) {
    error_log("ip: |" . $_SERVER['REMOTE_ADDR'] . "| session: |". session_id(). "| Too many login requests",);
    ErrorResponse::makeErrorResponse(429, "Too many requests");
    exit;
}

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
