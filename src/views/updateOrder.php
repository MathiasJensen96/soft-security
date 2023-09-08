<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orderlines.php"?>
<?php
if($userconn) {
        $sql = "SELECT * FROM securitydb.orderline WHERE orderId = '$id'";
        $result = $userconn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        $orderline = new orderlines($row['productId'], $row['orderId'], $row['quantity']);

        if(!empty($_POST['productId']) && !empty($_POST['quantity'])) {
            $productId = htmlspecialchars($_POST['productId']);
            $quantity = htmlspecialchars($_POST['quantity']);
            if($quantity != $orderline->getQuantity()) {
                $sql = "UPDATE securitydb.orderline SET quantity = '$quantity' WHERE productId = '$productId' AND orderId = '$id'";
                $result = $userconn->query($sql);
                echo "quantity was updated! ";
            }
        } else {
            echo "ProductID or quantity had no input. ";
        }
    }
?>