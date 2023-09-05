<?php include __DIR__ . "/../db/dbconn.php"?>
<?php
    $response = array();

    if ($userconn) {
        $sql = "SELECT * FROM product";
        $result = $userconn->query($sql);

        if ($result) {
            header("Content-Type: JSON");
            $i = 0;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $response[$i]['id'] = $row ['id'];
                $response[$i]['name'] = $row ['name'];
                $response[$i]['description'] = $row ['description'];
                $response[$i]['price'] = $row ['price'];
                $i++;
            }
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    } else {
        echo "Failed to connect to DB";
    }
?>