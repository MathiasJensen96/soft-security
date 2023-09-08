<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orders.php"?>
<?php include __DIR__ . "/../entities/orderlines.php"?>
<?php
    $orderlines = array();
    
    if ($userconn) {
        $sql = "SELECT * FROM securitydb.order WHERE id = '$id'";
        $result = $userconn->query($sql);

        if ($result) {
            header("Content-Type: application/json");
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                
                $orderlineRows = $userconn->query("SELECT * FROM securitydb.orderline WHERE orderId = '$id'");
                $i = 0;
                while($orderlineRow = $orderlineRows->fetch(PDO::FETCH_ASSOC)) {
                    $orderline = new orderlines($orderlineRow['productId'], $orderlineRow['orderId'], $orderlineRow['quantity']);

                    $orderlines[$i] = $orderline;
                    $i++;
                }
                
                $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderlines);
            }
            echo json_encode($order, JSON_PRETTY_PRINT);
        }
    } else {
        echo "Failed to connect to DB";
    }
?>