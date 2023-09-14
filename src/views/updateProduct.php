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
$accessControl->validateAccess('updateProduct', 'admin');

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    ErrorResponse::makeErrorResponse(405, "Method not allowed");
    exit;
}

if (!is_csrf_valid()) {
    ErrorResponse::makeErrorResponse(400, "CSRF token invalid");
    exit;
}

$validator = new InputValidator();
$validator->id($id);
$validator->product($_POST);

if($adminconn) {
    $sql = "SELECT * FROM product WHERE id = ?";
    $result = $adminconn->prepare($sql);
    $result->execute([$id]);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    $product = new products($row['id'], $row['name'], $row['description'], $row['price']);

    if(!empty($_POST['name'])) {
        $name = $_POST['name'];
        if($name != $product->getName()) {
            $sql = "UPDATE product SET name = ? WHERE id = ?";
            $result = $adminconn->prepare($sql);
            $result->execute([$name, $id]);
            echo "Name was updated! ";
        }
    } else {
        echo "Name had no input. ";
    }

    if(!empty($_POST['description'])) {
        $description = $_POST['description'];
        if($description != $product->getdescription()) {
            $sql = "UPDATE product SET description = ? WHERE id = ?";
            $result = $adminconn->prepare($sql);
            $result->execute([$description, $id]);
            echo "Description was updated! ";
        }
    } else {
        echo "Description had no input. ";
    }

    if(!empty($_POST['price'])) {
        $price = $_POST['price'];
        if($price != $product->getprice()) {
            $sql = "UPDATE product SET price = ? WHERE id = ?";
            $result = $adminconn->prepare($sql);
            $result->execute([$price, $id]);
            echo "Price was updated! ";
        }
    } else {
        echo "Price had no input. ";
    }
}