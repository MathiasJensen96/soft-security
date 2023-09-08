<?php include __DIR__ . "/../db/dbconn.php"?>
<?php include __DIR__ . "/../entities/orders.php"?>
<?php include __DIR__ . "/../entities/users.php"?>
<?php include __DIR__ . "/../entities/orderlines.php"?>
<?php
session_start();

$productId = htmlspecialchars($_POST['productId']);
$quantity = htmlspecialchars($_POST['quantity']);
$email = $_SESSION['email'];

if($userconn) {
    $userconn->query("INSERT INTO securitydb.order (date, User_email) VALUES ((SELECT SYSDATE()),'$email')");

    $sql = "SELECT LAST_INSERT_ID()";
    $getLastId = $userconn->query($sql);
    $lastID = $getLastId->fetch();

    $result = $userconn->query("SELECT * FROM securitydb.order WHERE id = '$lastID[0]'");
    
    if($result) {
        header("Content-Type: JSON");
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $orderline = new orderlines($productId, $lastID[0], $quantity);
            $order = new orders($row['id'], $row['status'], $row['date'], $row['User_email'], $orderline);
        }
        echo json_encode($order, JSON_PRETTY_PRINT);
        $userconn->query("INSERT INTO securitydb.orderline (productId, orderId, quantity) VALUES ($productId, $lastID[0], $quantity)");
    }
}
?>