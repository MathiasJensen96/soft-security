<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/users.php";
require_once __DIR__ . "/../entities/orderlines.php";
require_once __DIR__ . "/../security/InputValidator.php";

use security\InputValidator;

session_start();

$validator = new InputValidator();
$validator->orderlines($_POST);

$orderlines = $_POST['orderlines'];
$orderline = array();
$email = $_SESSION['email'];

if($userconn) {
    $userconn->beginTransaction();
    $stmt = $userconn->prepare("INSERT INTO securitydb.order (date, User_email) VALUES (NOW(),?)");
    $stmt->execute([$email]);

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
        $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderline);
        $userconn->commit();

        header("Content-Type: application/json");
        echo json_encode($order, JSON_HEX_TAG | JSON_PRETTY_PRINT);
    }
}