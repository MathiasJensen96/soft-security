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
    <div style="padding-left:16px;text-align: center">
        <h1>Products</h1>
        <h2>All products</h2>
        <div class="container">
            <form method="GET" action="/products">
                <button type="submit">Get all products</button>
            </form>
        </div>
        <h2>Find product</h2>
        <div class="container">
            <form method="GET" action="/getProduct">
                <label>
                    Get product by ID:
                    <input type="number" min="1" placeholder="Enter ID" name="id" required>
                <button type="submit">Find product</button>
            </form>
        </div>

        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {?>
            <h2>Create product</h2>
            <div class="container">
                <form method="POST" action="/products">
                    <?php set_csrf() ?>
                    <label>
                        Name:
                        <input type="text" placeholder="Enter product name" name="name" required>
                    </label>
                    <label>
                        Price:
                        <input type="number" step="0.01" min="0.01" placeholder="Enter product price" name="price" required>
                    </label>
                    <label>
                        Description:
                        <input type="text" placeholder="Enter product description" name="description" required>
                    </label>
                    <button type="submit" name="create">Create</button>
                </form>
            </div>
            <h2>Update product</h2>
            <div class="container">
                <form method="POST" action="/updateProduct">
                    <?php set_csrf() ?>
                    <label>
                        Product ID:
                        <input type="number" min="1" placeholder="Enter ID" name="id" required>
                    </label>
                    <label>
                        New name:
                        <input type="text" placeholder="Enter new product name" name="name" required>
                    </label>
                    <label>
                        New price:
                        <input type="number" step="0.01" min="0.01" placeholder="Enter product price" name="price" required>
                    </label>
                    <label>
                        New description:
                        <input type="text" placeholder="Enter new product description" name="description" required>
                    </label>
                    <button type="submit" name="update">Update</button>
                </form>
            </div>
            <h2>Delete product</h2>
            <div class="container">
                <form method="POST" action="/deleteProduct">
                    <?php set_csrf() ?>
                    <label>
                        ID of product to delete:
                        <input type="number" min="1" placeholder="Enter ID" name="id" required>
                    </label>
                    <button type="submit" name="delete">Terminate</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>