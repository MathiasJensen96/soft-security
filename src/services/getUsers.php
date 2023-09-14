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
$accessControl->validateAccess('getUsers', 'admin');

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

$userDao = new UserDao;

$users = $userDao->getAllUsers();
error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " queried all users\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
header("Content-Type: application/json");
echo json_encode(array_map(fn($u) => $u->htmlEncode(), $users), JSON_PRETTY_PRINT);