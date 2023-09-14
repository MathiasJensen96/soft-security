<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="views/style.css" rel="stylesheet" />
    <title>Orders</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div style="padding-left:16px;text-align: center">
        <h1>Orders</h1>
        <h2>All orders</h2>
        <div class="container">
            <form method="GET" action="/orders">
                <button type="submit">Get all orders</button>
            </form>
        </div>
        <h2>Find order</h2>
        <div class="container">
            <form method="GET" action="/getOrder">
                <label>
                    Get order by ID:
                    <input type="number" min="1" placeholder="Enter ID" name="id" required>
                    <button type="submit">Find order</button>
            </form>
        </div>

        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {?>
            <p>TODO: forms for create, update and delete orders</p>
        <?php } ?>
</body>
</html>