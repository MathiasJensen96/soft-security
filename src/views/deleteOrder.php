<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/orders.php";
require_once __DIR__ . "/../entities/orderlines.php";

$response = array();
$orderlines = array();

if($userconn) {
    $userconn->query("DELETE FROM securitydb.orderline WHERE orderId = '$id'");
    $userconn->query("DELETE FROM securitydb.order WHERE id = '$id'");


    $sql = "SELECT * FROM securitydb.order";
    $result = $userconn->query($sql);

    if ($result) {
        header("Content-Type: application/json");
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $orderlineRows = $userconn->query("SELECT * FROM securitydb.orderline WHERE orderId = '$id'");

            if($orderlineRows->rowCount()) {
                $k = 0;
                while($orderlineRow = $orderlineRows->fetch(PDO::FETCH_ASSOC)) {
                    $orderline = new orderlines($orderlineRow['productId'], $orderlineRow['orderId'], $orderlineRow['quantity']);

                    $orderlines[$k] = $orderline;
                    $k++;
                }
            } else {
                $orderlines = [];
            }

            $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderlines);

            $response[$i]= $order;
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "Failed to delete product";
    }
}