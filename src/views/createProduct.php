<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('createProduct', 'admin');

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

if (!is_csrf_valid()) {
    ErrorResponse::makeErrorResponse(400, "CSRF token invalid");
    exit;
}

$validator = new InputValidator();
$validator->product($_POST);

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

if($adminconn) {
    $sql = "INSERT INTO product (name, description, price) VALUES (?, ?, ?)";
    $stmt = $adminconn->prepare($sql);
    $result = $stmt->execute([$name, $description, $price]);

    if($result) {
        http_response_code(201);
    } else {
        ErrorResponse::makeErrorResponse(500, "Something went wrong");
    }
}