<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/products.php"?>
<?php
    if ($userconn) {
        $sql = "SELECT * FROM product WHERE id = '$id'";
        $result = $userconn->query($sql);

        if ($result) {
            header("Content-Type: JSON");
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $product = new products($row['id'], $row['name'], $row['description'], $row['price']);
            }
            echo json_encode($product, JSON_PRETTY_PRINT);
        }
    } else {
        echo "Failed to connect to DB";
    }
?>