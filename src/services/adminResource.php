<?php

require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../db/UserDao.php';
require_once __DIR__ . '/../error_handling/ErrorResponse.php';
require_once __DIR__ . '/../security/InputValidator.php';
require_once __DIR__ . '/../security/outputEncoder.php';

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

    $id = $_GET['id'];
    $inputValidator->email($id);
//    error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find user with invalid id: " . $id);
//    ErrorResponse::makeErrorResponse(400, "Invalid id: $id");

    $user = $userDao->getUserById($id);
    if (empty($user)) {
        error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find non-existent user: " . $id);
        ErrorResponse::makeErrorResponse(404, "User not found with id: $id");
        exit;
    }
    error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'get user' endpoint and found: " . json_encode($user) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
    header("Content-Type: application/json");

    echo htmlEncodedJson($user);
}

// UPDATE USER FUNKTIONALITET HER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update'])) {

        $id = $_POST['id'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        if ($inputValidator->email($email) && !empty($role)) {
            if ($userDao->getUserById($id)) {

                $userDao->updateUser($id, $email, $role);
                $updatedUser = $userDao->getUserById($id);
                error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'update user' endpoint, updated user : " . json_encode($updatedUser) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
                http_response_code(200);
                header("Content-Type: application/json");

                echo json_encode($updatedUser, JSON_HEX_TAG | JSON_PRETTY_PRINT);
            } else {
                error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update information of non-existent user: " . $email);
                ErrorResponse::makeErrorResponse(404, "User not found with id: $id");
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

        $id = $_POST['id'];
        $inputValidator->id($id);
        // TODO: put error logging in input validator
//        error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete user with invalid id: " . $id);
//        ErrorResponse::makeErrorResponse(400, "Invalid id: $id");

        if ($userDao->getUserById($id)) {
            $userDao->deleteUser($id);
            error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'delete user' endpoint and deleted: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);

            return http_response_code(200);
        } else {
            error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete non-existent user: " . $id);
            ErrorResponse::makeErrorResponse(404, "User not found with id: $id");
        }
    }
}
