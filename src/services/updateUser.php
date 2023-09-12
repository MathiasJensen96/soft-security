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
$accessControl->validateAccess('getUser', 'admin');

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

$inputValidator = new InputValidator();
$userDao = new UserDao;

$inputValidator->id($id);
$inputValidator->user($_POST);
//    error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update user" . $id . " with invalid data");

if (!$userDao->getUserById($id)) {
    error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update non-existent user: " . $id);
    ErrorResponse::makeErrorResponse(404, "User not found with id: $id");
    exit;
}

$userDao->updateUser($id, $_POST['email'], $_POST['role']);
$updatedUser = $userDao->getUserById($id);

error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'update user' endpoint, updated user : " . json_encode($updatedUser) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
header("Content-Type: application/json");
echo htmlEncodedJson($updatedUser);