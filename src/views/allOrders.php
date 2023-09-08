<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orders.php"?>
<?php include __DIR__ . "/../entities/orderlines.php"?>
<?php
    $response = array();
    $orderlines = array();

    if ($userconn) {
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
        }
    } else {
        echo "Failed to connect to DB";
    }
?>