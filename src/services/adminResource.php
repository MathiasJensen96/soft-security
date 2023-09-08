<?php

require_once __DIR__ . '/../controllers/AccessController.php';
require_once __DIR__ . '/../db/UserDao.php';
require_once __DIR__ . '/../security/InputValidator.php';


use controllers\AccessController;
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

        header("Content-Type: JSON");
        $user = $userDao->getUser($userEmail);
        echo json_encode($user, JSON_PRETTY_PRINT);
        error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " succesfully used 'get user' endpoint and found: " . json_encode($user) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
    } else {
        error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to find a non-existent user: " . $userEmail);
        echo "User doesn't exist";
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
                http_response_code(200);
                error_log(date('l jS \of F Y h:i:s A') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " succesfully used 'update user' endpoint, updated user : " . json_encode($updatedUser) . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
                echo "Updated user is: " . json_encode($updatedUser);
            } else {
                error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update information of non-existent user: " . $email);
                echo "no user: " . $email . " exists";
            }
        }
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
                return http_response_code(200);
            } else {
                error_log("User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update information of non-existent user: " . $deleteUserEmail);
                echo "no user: " . $deleteUserEmail . " exists";
            }
        }
    }
}
