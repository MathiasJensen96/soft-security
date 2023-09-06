<?php

require_once __DIR__ . '/../security/AuthorizationManager.php';
include __DIR__ . '/../db/UserDao.php';

use security\AuthorizationManager;

session_start();

$authorizationManager = new AuthorizationManager();
if (!$authorizationManager->requireRole('admin')) {
    echo "You are not authorised!";
    return http_response_code(403);
} else {
    $userDao = new UserDao;
    //echo "Welcome, admin.";


    // update og delete skal over i deres egen fil
    // de virker men de er lidt scuffed fordi de er i samme fil

    // LAV UPDATE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST['email']; // $_PUT findes ikke som superglobal?
        $role = $_POST['role'];
        $newEmail = $_POST['newEmail'];
        if (!empty($email) && !empty($role) && !empty($newEmail)) {
            $userDao->updateUser($newEmail, $role, $email);
        }
    }

    // LAV DELETE USER FUNKTION HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $deleteUserEmail = $_POST['deleteEmail'];
        if (!empty($deleteUserEmail)) {
            $userDao->deleteUser($deleteUserEmail);
        }
    }
}
