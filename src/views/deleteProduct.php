<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";

use controllers\AccessController;

$accessControl = new AccessController();
$accessControl->validateAccess('deleteProduct', 'admin');

if($adminconn) {
    $sql = "DELETE FROM product WHERE id = '$id'";
    $adminconn->query($sql);

    $sql = "SELECT * FROM product";
    $result = $adminconn->query($sql);

    if($result) {
        header("Content-Type: JSON");
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $product = new products($row['id'], $row['name'], $row['description'], $row['price']);

            $response[$i]= $product;
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "Failed to delete product";
    }
}