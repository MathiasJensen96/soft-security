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
$accessControl->validateAccess('deleteUser', 'admin');

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

if (!is_csrf_valid()) {
    ErrorResponse::makeErrorResponse(400, "CSRF token invalid");
    exit;
}

$inputValidator = new InputValidator();
$inputValidator->id($id);

$userDao = new UserDao;

if (!$userDao->getUserById($id)) {
    error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete non-existent user: " . $id);
    ErrorResponse::makeErrorResponse(404, "User not found with id: $id");
    exit;
}

$userDao->deleteUser($id);
error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " successfully used 'delete user' endpoint and deleted: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);