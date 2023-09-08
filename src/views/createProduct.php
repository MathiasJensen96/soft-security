<?php

require_once __DIR__ . "/../controllers/AccessController.php";
require_once __DIR__ . "/../db/dbconn.php";
require_once __DIR__ . "/../entities/products.php";

use controllers\AccessController;

session_start();

$accessControl = new AccessController();
$accessControl->validateAccess('createProduct', 'admin');

$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$price = htmlspecialchars($_POST['price']);

if($adminconn) {
    $sql = "INSERT INTO product (name, description, price) VALUES ('$name', '$description', '$price')";
    $result = $adminconn->query($sql);

    if($result) {
        echo "Product was created!";
    } else {
        echo "Failed to create product";
    }
}