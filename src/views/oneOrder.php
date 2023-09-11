<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";

use error_handling\ErrorResponse;

session_start();

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
    echo json_encode($order, JSON_PRETTY_PRINT);

} else {
    echo "Failed to connect to DB";
}