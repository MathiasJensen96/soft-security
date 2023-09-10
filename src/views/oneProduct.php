<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use error_handling\ErrorResponse;
use security\InputValidator;

$validator = new InputValidator();
$validator->id($id);

if ($userconn) {
    $sql = "SELECT * FROM product WHERE id = ?";
    $stmt = $userconn->prepare($sql);
    $stmt->bindParam(1, $id);
    $result = $stmt->execute();

    if (!$result) {
        ErrorResponse::makeErrorResponse(500, "Failed to execute query");
        exit;
    }

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        header("Content-Type: application/json");
        $product = new products($row['id'], $row['name'], $row['description'], $row['price']);
        echo json_encode($product, JSON_PRETTY_PRINT);
    } else {
        ErrorResponse::makeErrorResponse(404, "Product not found with id: $id");
    }
} else {
    echo "Failed to connect to DB";
}