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

        <h1>Update user information here</h1>
        <form method="PUT" action="/users/{id}">
            <div class="container">
                <label>Old email : </label>
                <input type="text" placeholder="Enter user Email" name="email" required>
                <label>Role : </label>
                <input type="text" placeholder="Enter new user role" name="role" required>
                <label>New email : </label>
                <input type="text" placeholder="Enter new user email" name="newEmail" required>
                <button type="submit">Update</button>
            </div>
        </form>
        
        <h1>Delete user here</h1>
        <form method="DELETE" action="/users/{id}">
            <div class="container">
                <label>User email to delete: </label>
                <input type="text" placeholder="Enter Email" name="email" required>
                <button type="submit">Terminate</button>
            </div>
        </form>

</body>

</html>