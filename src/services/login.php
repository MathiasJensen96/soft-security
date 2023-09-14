<?php

require_once __DIR__ . "/../db/UserDao.php";
require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../security/InputValidator.php';
require_once __DIR__ . '/../security/RateLimiter.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/getIp.php';

use error_handling\ErrorResponse;
use security\AuthenticationManager;
use security\InputValidator;
use security\RateLimiter;

session_start();

$ip = getIp();

$validator = new InputValidator();
$validator->credentials($_POST);

$rateLimiter = new RateLimiter(5, 60);
if (!$rateLimiter->isAllowed($ip)) {
    error_log("ip: | $ip | session: |". session_id(). "| Too many login requests",);
    ErrorResponse::makeErrorResponse(429, "Too many requests");
    exit;
}

$userDao = new UserDao();
$authenticationManager = new AuthenticationManager();

$user = $userDao->getUserByEmail($_POST['email']);
if ($user && password_verify($_POST['password'], $user->getPassword())) { // Verifying password
    $authenticationManager->createSession();
    $_SESSION['id'] = $user->getId();
    $_SESSION['role'] = $user->getRole();
    $_SESSION['email'] = $user->getEmail();
    set_csrf(); // now it doesn't redirect anymore

    if ($_SESSION['role'] === "admin") { // Maybe not the best practise to redirect here
        header("Location: /admin-page");
    }
} else {
    error_log("ip: | $ip | Attempted login to user: " . $_POST['email']);
    ErrorResponse::makeErrorResponse(401, "Incorrect username or password");
}