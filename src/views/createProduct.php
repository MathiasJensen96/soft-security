<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/products.php"?>
<?php
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
?>