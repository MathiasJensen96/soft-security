<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../error_handling/ErrorResponse.php";
require_once __DIR__ . "/../security/InputValidator.php";

use controllers\AccessController;
use error_handling\ErrorResponse;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('updateOrder');

$validator = new InputValidator();
$validator->id($id);
$validator->orderlines($_POST);

$orderlines = $_POST['orderlines'];
$currOrderlines = array();

if($userconn) {
    //$userconn->beginTransaction();
    $stmt = $userconn->prepare("SELECT * FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || ($row['user'] !== $_SESSION['id'] && $_SESSION['role'] !== "admin")) {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update order with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
        exit;
    }

    $stmt = $userconn->prepare("SELECT * FROM securitydb.orderline WHERE orderid = ?");
    $stmt->execute([$id]);

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $orderline = new orderlines($row['productId'], $row['orderId'], $row['quantity']);
        array_push($currOrderlines, $orderline);
    }

    foreach($orderlines as $updateOrderline) {
        $newOrderline = new orderlines($updateOrderline['productId'], $id, $updateOrderline['quantity']);
        $match = false;

        for($i = 0; $i < count($currOrderlines); $i++) {
            if($newOrderline->getProductId() == $currOrderlines[$i]->productId) {
                if($newOrderline->getQuantity() == 0) {
                    $stmt = $userconn->prepare("DELETE FROM securitydb.orderline WHERE orderId = ? AND productId = ?");
                    $stmt->execute([$id, $newOrderline->getProductId()]);
                }
                else if($newOrderline->getQuantity() != $currOrderlines[$i]->quantity) {
                    $stmt = $userconn->prepare("UPDATE securitydb.orderline SET quantity = ? WHERE productId = ? AND orderId = ?");
                    $stmt->execute([$newOrderline->getQuantity(), $newOrderline->getProductId(), $id]);
                }
                $match = true;
            }
        }
        if($match == false) {
            $stmt = $userconn->prepare("INSERT INTO securitydb.orderline (productId, orderId, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$newOrderline->getProductId(), $id, $newOrderline->getQuantity()]);
        }
    }
}