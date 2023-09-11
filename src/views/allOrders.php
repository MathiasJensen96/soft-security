<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";

use controllers\AccessController;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('allOrders');

if ($userconn) {
    $response = [];

    if ($_SESSION['role'] === 'admin') {
        $sql = "SELECT * FROM securitydb.order";
        $stmt = $userconn->query($sql);
    } else {
        $sql = "SELECT * FROM securitydb.order WHERE User_email = ?";
        $stmt = $userconn->prepare($sql);
        $stmt->execute([$_SESSION['email']]);
    }

    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
    echo "Failed to connect to DB";
}