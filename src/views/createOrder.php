<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/users.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../security/InputValidator.php";

use controllers\AccessController;
use security\InputValidator;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('createOrder');

$validator = new InputValidator();
$validator->orderlines($_POST);

$orderlines = $_POST['orderlines'];
$orderline = array();

if($userconn) {
    $userconn->beginTransaction();
    $stmt = $userconn->prepare("INSERT INTO securitydb.order (date, user) VALUES (NOW(),?)");
    $stmt->execute([$_SESSION['id']]);

    $sql = "SELECT LAST_INSERT_ID()";
    $getLastId = $userconn->query($sql);
    $lastID = $getLastId->fetch();

    $stmt = $userconn->prepare("SELECT * FROM securitydb.order WHERE id = ?");
    $result = $stmt->execute([$lastID[0]]);
    
    if($result) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach($orderlines as $currOrderline) {
            $newOrderline = new orderlines($currOrderline['productId'], $lastID[0], $currOrderline['quantity']);
            $stmt = $userconn->prepare("INSERT INTO securitydb.orderline (productId, orderId, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$newOrderline->getProductId(), $lastID[0], $newOrderline->getQuantity()]);
            array_push($orderline, $newOrderline);
        }
        $order = new orders($row['id'], $row['status'], $row['date'], $row['user'], $orderline);
        $userconn->commit();

        header("Content-Type: application/json");
        echo json_encode($order, JSON_HEX_TAG | JSON_PRETTY_PRINT);
    }
}