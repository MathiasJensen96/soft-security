<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        .topnav a {
            float: right;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
    </style>
    <title>Admin Page</title>
</head>

<body>

    <div class="topnav">
        <a href="/logout">Logout</a>
    </div>
    <div style="padding-left:16px">
        <h1>Hello World!</h1>

        <h1>Find user information here</h1>
        <form method="GET" action="/users/$id">
            <div class="container">
                <label>Get user by email : </label>
                <input type="text" placeholder="Enter user Email" name="userEmail" >
                <button type="submit">Get user</button>
            </div>
        </form>

        <h1>Update user information here</h1>
        <form method="POST" action="/update-users/$id">
            <div class="container">
                <label>Old email : </label>
                <input type="text" placeholder="Enter user Email" name="email" >
                <label>Role : </label>
                <input type="text" placeholder="Enter new user role" name="role" >
                <label>New email : </label>
                <input type="text" placeholder="Enter new user email" name="newEmail" >
                <button type="submit" name="update">Update</button>
            </div>
        </form>

        <h1>Delete user here</h1>
        <form method="POST" action="/delete-users/$id">
            <div class="container">
                <label>User email to delete: </label>
                <input type="text" placeholder="Enter Email" name="deleteEmail" >
                <button type="submit" name="delete">Terminate</button>
            </div>
        </form>

</body>

</html>