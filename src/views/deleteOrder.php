<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use error_handling\ErrorResponse;
use security\InputValidator;

$validator = new InputValidator();
$validator->id($id);

session_start();

if ($userconn) {
    $response = [];

    $stmt = $userconn->prepare("SELECT * FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || ($row['User_email'] !== $_SESSION['email'] && $_SESSION['role'] !== "admin")) {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to delete order with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
        exit;
    }

    $userconn->beginTransaction();
    $stmt = $userconn->prepare("DELETE FROM securitydb.orderline WHERE orderId = ?");
    $stmt->execute([$id]);
    $stmt = $userconn->prepare("DELETE FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);
    $userconn->commit();

    $sql = "SELECT * FROM securitydb.order";
    $result = $userconn->query($sql);

    if ($result) {
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $orderlines = [];

            $id = $row['id'];
            $linesStmt = $userconn->prepare("SELECT * FROM securitydb.orderline WHERE orderId = ?");
            $linesStmt->execute([$id]);

            $k = 0;
            while($orderlineRow = $linesStmt->fetch(PDO::FETCH_ASSOC)) {
                $orderline = new orderlines($orderlineRow['productId'], $orderlineRow['orderId'], $orderlineRow['quantity']);

                $orderlines[$k] = $orderline;
                $k++;
            }

            $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderlines);

            $response[$i]= $order;
            $i++;
        }
        header("Content-Type: application/json");
        echo json_encode($response, JSON_HEX_TAG | JSON_PRETTY_PRINT);
    } else {
        echo "Failed to delete product";
    }
}