<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('oneOrder');

$validator = new InputValidator();
$validator->id($id);

if ($userconn) {
    $sql = "SELECT * FROM securitydb.order WHERE id = ?";
    $stmt = $userconn->prepare($sql);
    $stmt->bindParam(1, $id);
    $result = $stmt->execute();

    if (!$result) {
        ErrorResponse::makeErrorResponse(500, "Failed to execute query");
        exit;
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || ($row['User_email'] !== $_SESSION['email'] && $_SESSION['role'] !== "admin")) {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to view order with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
        exit;
    }

    $orderlines = [];

    $linesStmt = $userconn->prepare("SELECT * FROM securitydb.orderline WHERE orderId = ?");
    $linesStmt->execute([$id]);

    foreach ($linesStmt->fetchAll(PDO::FETCH_ASSOC) as $lineRow) {
        $orderline = new orderlines($lineRow['productId'], $lineRow['orderId'], $lineRow['quantity']);
        $orderlines[] = $orderline;
    }

    $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderlines);
    header("Content-Type: application/json");
    echo json_encode($order, JSON_HEX_TAG | JSON_PRETTY_PRINT);

} else {
    echo "Failed to connect to DB";
}