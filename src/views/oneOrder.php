<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";

use error_handling\ErrorResponse;

session_start();

if ($userconn) {
    $sql = "SELECT * FROM securitydb.order WHERE id = ? AND User_email = ?";
    $stmt = $userconn->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $_SESSION['email']);
    $result = $stmt->execute();

    if (!$result) {
        ErrorResponse::makeErrorResponse(500, "Failed to execute query");
        exit;
    }

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
    }

} else {
    echo "Failed to connect to DB";
}