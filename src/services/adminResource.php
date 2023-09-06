<?php

require_once __DIR__ . '/../security/AuthorizationManager.php';
include '../db/UserDao.php';

use security\AuthorizationManager;

session_start();

$authorizationManager = new AuthorizationManager();
if (!$authorizationManager->requireRole('admin')) {
    echo "You are not authorised!";
    return http_response_code(403);
} else {
    $userDao = new UserDao;
    echo "Welcome, admin.";
    // LAV UPDATE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $email = $_PUT['email']; // $_PUT findes ikke som superglobal?
        $role = $_PUT['role'];
        $newEmail = $_PUT['newEmail'];
        $userDao->updateUser($email, $role, $newEmail);
    }
    
    // LAV DELETE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "DELETE") {} 
}
