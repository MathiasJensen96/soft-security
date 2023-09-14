<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="views/style.css" rel="stylesheet" />
        <title>Admin Page</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div style="padding-left:16px;text-align: center">
            <h1>Hello World!</h1>
            <h2>Find user information here</h2>
            <div class="container">
                <form method="GET" action="/getUser">
                    <label>
                        Get user by ID:
                        <input type="number" min="1" placeholder="Enter user ID" name="id" required>
                    </label>
                    <button type="submit">Get user</button>
                </form>
            </div>
            <h2>Update user information here</h2>
            <div class="container">
                <form method="POST" action="/updateUser">
                    <?php set_csrf() ?>
                    <label>
                        User ID:
                        <input type="number" min="1" placeholder="Enter user ID" name="id" required>
                    </label>
                    <label>
                        Role:
                        <input type="text" placeholder="Enter new user role" name="role" required>
                    </label>
                    <label>
                        New email:
                        <input type="email" placeholder="Enter new user email" name="email" required>
                    </label>
                    <button type="submit" name="update">Update</button>
                </form>
            </div>
            <h2>Delete user here</h2>
            <div class="container">
                <form method="POST" action="/deleteUser">
                    <?php set_csrf() ?>
                    <label>
                        ID of user to delete:
                        <input type="number" min="1" placeholder="Enter user ID" name="id" required>
                    </label>
                    <button type="submit" name="delete">Terminate</button>
                </form>
            </div>
        </div>
    </body>
</html>