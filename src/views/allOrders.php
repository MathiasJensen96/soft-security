<?php include __DIR__ . "/../db/dbconn.php"?>
<?php
    $sql = "SELECT * FROM securitydb.order";
    $result = $userconn->query($sql);

    if ($result->rowCount() > 0) {
        echo "<table><tr><th>Id</th><th>Status</th><th>Date</th><th>User Email</th></tr>";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>".$row["id"]."</td><td>".$row["status"]."</td><td>".$row["date"]."</td><td>".$row["User_email"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo"No results found!";
    }
?>