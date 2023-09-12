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
