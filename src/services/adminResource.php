<?php

require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../security/AuthorizationManager.php';
require_once __DIR__ . '/../db/UserDao.php';

use security\AuthenticationManager;
use security\AuthorizationManager;

session_start();

$authenticationManager = new AuthenticationManager();
$authorizationManager = new AuthorizationManager();

if ($authenticationManager->isExpired()) {
    echo "Your session has expired.";
    exit;
}
if (!$authorizationManager->requireRole('admin')) {
    echo "You are not authorised!";
    return http_response_code(403);
} else {
    $userDao = new UserDao;
    //echo "Welcome, admin.";
    // LAV UPDATE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST['email']; // $_PUT findes ikke som superglobal?
        $role = $_POST['role'];
        $newEmail = $_POST['newEmail'];
        $userDao->updateUser($newEmail, $role, $email);
        
    }

    // LAV DELETE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    }
}
