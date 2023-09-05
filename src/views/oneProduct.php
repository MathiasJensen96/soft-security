<?php include __DIR__ . "/../db/dbconn.php"?>
<?php
    $sql = "SELECT * FROM product WHERE id = '$id'";
    $result = $userconn->query($sql);

    if ($result->rowCount() > 0) {
        echo "<table><tr><th>Id</th><th>Name</th><th>Description</th><th>Price</th></tr>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["description"]."</td><td>".$row["price"]."</td></tr>";
        }
        echo "</table>";
    } else {
    echo"No results found!";
    }
?>