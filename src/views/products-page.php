<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="views/style.css" rel="stylesheet" />
    <title>Products</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Products</h1>
    <p>TODO: get all, get one</p>

    <?php
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {?>
        <p>TODO: forms for create, update and delete products</p>
    <?php } ?>
</body>
</html>