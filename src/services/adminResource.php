<?php

require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../db/UserDao.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/InputValidator.php';


use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('admin page', 'admin');

$inputValidator = new InputValidator();
$userDao = new UserDao;

// FIND USER FUNKTIONALITET HER
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $userEmail = $_GET['userEmail'];
    if ($inputValidator->email($userEmail)) {
        $user = $userDao->getUser($userEmail);
        if (empty($user)) {
            error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find non-existent user: " . $userEmail);
            ErrorResponse::makeErrorResponse(404, "User not found with email: $userEmail");
            exit;
        }
        error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'get user' endpoint and found: " . json_encode($user) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        header("Content-Type: application/json");
        //TODO: Maybe output encode here
        echo json_encode($user, JSON_PRETTY_PRINT);
    } else {
        error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find user with invalid email: " . $userEmail);
        ErrorResponse::makeErrorResponse(400, "Invalid email: $userEmail");
    }
}

// UPDATE USER FUNKTIONALITET HER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update'])) {

        $email = $_POST['email'];
        $role = $_POST['role'];
        $newEmail = $_POST['newEmail'];
        if ($inputValidator->email($email) && !empty($role) && $inputValidator->email($newEmail)) {
            if ($userDao->getUser($email)) {

                $userDao->updateUser($newEmail, $role, $email);
                $updatedUser = $userDao->getUser($newEmail);
                error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'update user' endpoint, updated user : " . json_encode($updatedUser) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
                http_response_code(200);
                header("Content-Type: application/json");
                //TODO: Maybe output encode here
                echo json_encode($updatedUser);
            } else {
                error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update information of non-existent user: " . $email);
                ErrorResponse::makeErrorResponse(404, "User not found with email: $userEmail");
            }
        } else {
            error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update user with invalid data");
            ErrorResponse::makeErrorResponse(400, "Invalid data provided");
        }
        exit;
    }
}


// DELETE USER FUNKTIONALITET HER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete'])) {

        $deleteUserEmail = $_POST['deleteEmail'];
        if ($inputValidator->email($deleteUserEmail)) {
            if ($userDao->getUser($deleteUserEmail)) {
                $userDao->deleteUser($deleteUserEmail);
                error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'delete user' endpoint and deleted: " . $deleteUserEmail . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
                //TODO: Maybe output encode here and make it return the deleted user
                return http_response_code(200);
            } else {
                error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete non-existent user: " . $deleteUserEmail);
                ErrorResponse::makeErrorResponse(404, "User not found with email: $deleteUserEmail");
            }
        } else {
            error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete user with invalid email: " . $deleteUserEmail);
            ErrorResponse::makeErrorResponse(400, "Invalid email: $deleteUserEmail");
        }
    }
}
