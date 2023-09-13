<?php

require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";

$response = [];

if ($userconn) {
    $sql = "SELECT * FROM product";
    $result = $userconn->query($sql);

    if ($result) {
        header("Content-Type: application/json");
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $product = new products($row['id'], $row['name'], $row['description'], $row['price']);

            $response[$i]= $product->htmlEncode();
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
} else {
    echo "Failed to connect to DB";
}