<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";

use controllers\AccessController;
use error_handling\ErrorResponse;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('createProduct', 'admin');

$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$price = htmlspecialchars($_POST['price']);

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