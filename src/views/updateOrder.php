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

if($userconn) {
    $stmt = $userconn->prepare("SELECT * FROM securitydb.order WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || ($row['User_email'] !== $_SESSION['email'] && $_SESSION['role'] !== "admin")) {
        error_log(date('c') . " | User: " . $_SESSION['email'] . " with role: " . $_SESSION['role'] . " tried to update order with id: " . $id . "\n", 3, $_ENV['ADMIN_ENDPOINT_LOG']);
        ErrorResponse::makeErrorResponse(404, "Order not found with id: $id");
        exit;
    }

    $stmt = $userconn->prepare("SELECT * FROM securitydb.orderline WHERE orderid = ?");
    $stmt->execute([$id]);

    //hent gamle orderlines og lav et array
    //loop igennem formdata: 
        //ved product id match -> enten updater quantity eller delete orderline hvis 0
        //ved ingen match -> insert ny orderline.


    // foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    //     $orderline = new orderlines($row['productId'], $row['orderId'], $row['quantity']);

    //     foreach($orderlines as $currOrderline) {
    //         $newOrderline = new orderlines($currOrderline['productId'], $lastID[0], $currOrderline['quantity']);
    //         if($newOrderline->getProductId() == $orderline->getProductId()) {
    //             $orderline->setQuantity($newOrderline->getQuantity());
    //         }
    //     }


    // }


    if(!empty($_POST['productId']) && !empty($_POST['quantity'])) {
        $productId = htmlspecialchars($_POST['productId']);
        $quantity = htmlspecialchars($_POST['quantity']);
        if($quantity != $orderline->getQuantity()) {
            $sql = "UPDATE securitydb.orderline SET quantity = ? WHERE productId = ? AND orderId = ?";
            $stmt = $userconn->prepare($sql);
            $stmt->execute([$quantity, $productId, $id]);
            echo "quantity was updated! ";
        }
    } else {
        echo "ProductID or quantity had no input. ";
    }
}