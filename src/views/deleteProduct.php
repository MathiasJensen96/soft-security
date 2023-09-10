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
$accessControl->validateAccess('deleteProduct', 'admin');

$validator = new InputValidator();
$validator->id($id);

if($adminconn) {
    $sql = "DELETE FROM product WHERE id = ?";
    $stmt = $adminconn->prepare($sql);
    $stmt->execute([$id]);

    $sql = "SELECT * FROM product";
    $result = $adminconn->query($sql);

    if($result) {
        $response = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $product = new products($row['id'], $row['name'], $row['description'], $row['price']);
            $response[]= $product;
        }
        header("Content-Type: application/json");
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete product with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(500, "Failed to delete product");
    }
}