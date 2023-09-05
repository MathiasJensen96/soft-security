<?php include __DIR__ . "/../db/dbconn.php"?>
<?php
    $sql = "INSERT INTO product (name, description, price) values (?, ?, ?)";
    $result = $adminconn->query($sql);

    if ($result->rowCount() > 0) {
        echo "Product was added successfully!";
    } else {
        echo "Could not insert product into database";
    }
?>