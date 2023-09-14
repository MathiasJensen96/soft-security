<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="views/style.css" rel="stylesheet" />
    <title>Admin Page</title>
</head>

<body>
    <div class="topnav">
        <a href="/logout">Logout</a>
    </div>
    <div style="padding-left:16px;text-align: center">
        <h1>Hello World!</h1>

        <h1>Find user information here</h1>
        <div class="container">
            <form method="GET" action="/getUser">
                <label>
                    Get user by ID:
                    <input type="text" placeholder="Enter user ID" name="id" >
                </label>
                <button type="submit">Get user</button>
            </form>
        </div>

        <h1>Update user information here</h1>
        <div class="container">
            <form method="POST" action="/updateUser">
                <label>
                    User ID:
                    <input type="text" placeholder="Enter user ID" name="id" >
                </label>
                <label>
                    Role:
                    <input type="text" placeholder="Enter new user role" name="role" >
                </label>
                <label>
                    New email:
                    <input type="text" placeholder="Enter new user email" name="email" >
                </label>
                <button type="submit" name="update">Update</button>
            </form>
        </div>

        <h1>Delete user here</h1>
        <div class="container">
            <form method="GET" action="/deleteUser">
                <label>
                    ID of user to delete:
                    <input type="text" placeholder="Enter ID" name="id" >
                </label>
                <button type="submit" name="delete">Terminate</button>
            </form>
        </div>
    </div>
</body>

</html>