<?php

require_once __DIR__ . '/../security/AuthenticationManager.php';
require_once __DIR__ . '/../security/AuthorizationManager.php';
require_once __DIR__ . '/../db/UserDao.php';


use security\AuthenticationManager;
use security\AuthorizationManager;

session_start();

$authenticationManager = new AuthenticationManager();
$authorizationManager = new AuthorizationManager();

if (!$authenticationManager->validateSession()) {
    echo "Your session has expired.";
    exit;
}
if (!$authorizationManager->requireRole('admin')) {
    echo "You are not authorised!";
    return http_response_code(403);
} else {
    $userDao = new UserDao;
    //echo "Welcome, admin.";


    // UPDATE USER FUNKTIONALITET HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['update'])) {

            $email = $_POST['email'];
            $role = $_POST['role'];
            $newEmail = $_POST['newEmail'];
            if (!empty($email) && !empty($role) && !empty($newEmail)) {
                if ($userDao->getUser($email)) {

                    $userDao->updateUser($newEmail, $role, $email);
                    $updatedUser = $userDao->getUser($newEmail);
                    http_response_code(200);
                    return $updatedUser;
                } else {

                    echo "no user: " . $email . " exists";
                }
            }
        }
    }

    // DELETE USER FUNKTIONALITET HER
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['delete'])) {

            $deleteUserEmail = $_POST['deleteEmail'];
            if (!empty($deleteUserEmail)) {
                if ($userDao->getUser($deleteUserEmail)) {

                    $userDao->deleteUser($deleteUserEmail);
                    return http_response_code(200);
                } else {

                    echo "no user: " . $deleteUserEmail . " exists";
                }
            }
        }
    }
}
