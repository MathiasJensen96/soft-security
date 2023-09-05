<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orders.php"?>
<?php
    $response = array();

    if ($userconn) {
        $sql = "SELECT * FROM securitydb.order";
        $result = $userconn->query($sql);

        if ($result) {
            header("Content-Type: JSON");
            $i = 0;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email']);

                $response[$i]= $order;
                $i++;
            }
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    } else {
        echo "Failed to connect to DB";
    }
?>