<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orders.php"?>
<?php
    if ($userconn) {
        $sql = "SELECT * FROM securitydb.order WHERE id = '$id'";
        $result = $userconn->query($sql);

        if ($result) {
            header("Content-Type: JSON");
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email']);
            }
            echo json_encode($order, JSON_PRETTY_PRINT);
        }
    } else {
        echo "Failed to connect to DB";
    }

    //TODO: add orderlines
?>