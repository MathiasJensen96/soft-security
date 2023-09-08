<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";

use controllers\AccessController;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('updateProduct', 'admin');

if($adminconn) {
    $sql = "SELECT * FROM product WHERE id = '$id'";
    $result = $adminconn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    $product = new products($row['id'], $row['name'], $row['description'], $row['price']);

    if(!empty($_POST['name'])) {
        $name = htmlspecialchars($_POST['name']);
        if($name != $product->getName()) {
            $sql = "UPDATE product SET name = '$name' WHERE id = '$id'";
            $result = $adminconn->query($sql);
            echo "Name was updated! ";
        }
    } else {
        echo "Name had no input. ";
    }

    if(!empty($_POST['description'])) {
        $description = htmlspecialchars($_POST['description']);
        if($description != $product->getdescription()) {
            $sql = "UPDATE product SET description = '$description' WHERE id = '$id'";
            $result = $adminconn->query($sql);
            echo "Description was updated! ";
        }
    } else {
        echo "Description had no input. ";
    }

    if(!empty($_POST['price'])) {
        $price = htmlspecialchars($_POST['price']);
        if($price != $product->getprice()) {
            $sql = "UPDATE product SET price = '$price' WHERE id = '$id'";
            $result = $adminconn->query($sql);
            echo "Price was updated! ";
        }
    } else {
        echo "Price had no input. ";
    }
}